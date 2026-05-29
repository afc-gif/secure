<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'reference_token' => ['nullable', 'string', 'max:40'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $email = $this->string('email')->lower()->toString();
        $referenceToken = Str::upper($this->string('reference_token')->trim()->toString());

        logger()->info('Login attempt received.', [
            'email' => $email,
            'has_reference_token' => $referenceToken !== '',
            'ip' => $this->ip(),
        ]);

        $user = User::query()
            ->where('email', $email)
            ->where('status', 'active')
            ->first();

        if (! $user) {
            logger()->warning('Login failed: active user not found.', ['email' => $email]);
            $this->failAuthentication();
        }

        if (! $user->isAdmin() && $referenceToken === '') {
            logger()->warning('Login failed: member reference token missing.', ['email' => $email]);
            $this->failAuthentication();
        }

        if ($referenceToken !== '' && $user->reference_token !== $referenceToken) {
            logger()->warning('Login failed: reference token mismatch.', [
                'email' => $email,
                'user_id' => $user->id,
                'role' => $user->role,
            ]);
            $this->failAuthentication();
        }

        if (! Auth::guard('web')->attempt([
            'email' => $email,
            'password' => $this->string('password')->toString(),
            'status' => 'active',
        ], $this->boolean('remember'))) {
            logger()->warning('Login failed: password mismatch.', [
                'email' => $email,
                'user_id' => $user->id,
                'role' => $user->role,
            ]);
            $this->failAuthentication();
        }

        logger()->info('Login authenticated successfully.', [
            'email' => $email,
            'user_id' => $user->id,
            'role' => $user->role,
        ]);

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * @throws ValidationException
     */
    private function failAuthentication(): never
    {
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('The submitted partner credentials could not be verified.'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->string('reference_token').'|'.$this->ip());
    }
}

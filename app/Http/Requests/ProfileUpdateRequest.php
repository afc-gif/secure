<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'full_legal_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'regex:/^[0-9+\-\s()]{7,30}$/'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'string', 'max:80'],
            'country' => ['nullable', 'string', 'max:120'],
            'state' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:120'],
            'residential_address' => ['nullable', 'string', 'min:8', 'max:700'],
            'postal_code' => ['nullable', 'string', 'max:30'],
            'cash_app_handle' => ['nullable', 'string', 'regex:/^\$?[A-Za-z0-9_]{1,20}$/'],
            'occupation' => ['nullable', 'string', 'max:160'],
            'agricultural_interest_type' => ['nullable', 'string', 'max:120'],
            'ownership_interest_reason' => ['nullable', 'string', 'max:1000'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('cash_app_handle')) {
            return;
        }

        $handle = trim((string) $this->input('cash_app_handle'));

        if ($handle !== '' && ! str_starts_with($handle, '$')) {
            $handle = '$'.$handle;
        }

        $this->merge([
            'cash_app_handle' => $handle !== '' ? $handle : null,
        ]);
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Enter a valid phone number using digits, spaces, +, -, or parentheses.',
            'cash_app_handle.regex' => 'Enter a valid Cash App handle, for example $YourHandle.',
        ];
    }
}

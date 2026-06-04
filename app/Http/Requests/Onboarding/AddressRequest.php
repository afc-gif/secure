<?php

namespace App\Http\Requests\Onboarding;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isMember() ?? false;
    }

    public function rules(): array
    {
        return [
            'country' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'city' => ['required', 'string', 'max:120'],
            'residential_address' => ['required', 'string', 'min:8', 'max:700'],
            'postal_code' => ['required', 'string', 'max:30'],
            'cash_app_handle' => ['required', 'string', 'regex:/^\$?[A-Za-z0-9_]{1,20}$/'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $handle = trim((string) $this->input('cash_app_handle'));

        if ($handle !== '' && ! str_starts_with($handle, '$')) {
            $handle = '$'.$handle;
        }

        $this->merge([
            'cash_app_handle' => $handle,
        ]);
    }

    public function messages(): array
    {
        return [
            'cash_app_handle.required' => 'Cash App handle is required.',
            'cash_app_handle.regex' => 'Enter a valid Cash App handle, for example $YourHandle.',
        ];
    }
}

<?php

namespace App\Http\Requests\Onboarding;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IdentityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isMember() ?? false;
    }

    public function rules(): array
    {
        return [
            'full_legal_name' => ['required', 'string', 'min:3', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\-\s()]{7,30}$/'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'string', Rule::in(['female', 'male', 'non_binary', 'prefer_not_to_say'])],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Enter a valid phone number using digits, spaces, +, -, or parentheses.',
        ];
    }
}

<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSettlementProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isMember();
    }

    public function rules(): array
    {
        return [
            'bank_name' => ['required', 'string', 'max:120'],
            'account_name' => ['required', 'string', 'max:120'],
            'routing_number' => ['required', 'string', 'regex:/^\d{9}$/'],
            'account_number' => ['required', 'string', 'min:4', 'max:34', 'regex:/^[A-Za-z0-9-]+$/'],
            'account_type' => ['required', Rule::in(['checking', 'savings'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'bank_name' => trim((string) $this->input('bank_name')),
            'account_name' => trim((string) $this->input('account_name')),
            'routing_number' => preg_replace('/\D+/', '', (string) $this->input('routing_number')),
            'account_number' => trim((string) $this->input('account_number')),
            'account_type' => strtolower(trim((string) $this->input('account_type'))),
        ]);
    }

    public function messages(): array
    {
        return [
            'bank_name.required' => 'Bank name is required.',
            'account_name.required' => 'Account holder name is required.',
            'routing_number.required' => 'Routing number is required.',
            'routing_number.regex' => 'Enter a valid 9-digit routing number.',
            'account_number.required' => 'Account number is required.',
            'account_number.regex' => 'Account number may only contain letters, numbers, and hyphens.',
            'account_type.required' => 'Account type is required.',
        ];
    }
}

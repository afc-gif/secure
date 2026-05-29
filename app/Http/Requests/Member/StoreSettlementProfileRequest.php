<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class StoreSettlementProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isMember();
    }

    public function rules(): array
    {
        return [
            'bank_name' => ['required', 'string', 'max:255'],
            'account_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'min:8', 'max:34'],
            'routing_number' => ['nullable', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:2'],
            'currency' => ['required', 'string', 'size:3'],
        ];
    }

    public function messages(): array
    {
        return [
            'bank_name.required' => 'Bank name is required',
            'account_name.required' => 'Account holder name is required',
            'account_number.required' => 'Account number is required',
            'country.required' => 'Country is required',
            'currency.required' => 'Currency is required',
        ];
    }
}

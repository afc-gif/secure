<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContributionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isMember();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'batch_id' => ['nullable', 'exists:batches,id'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'currency' => ['required', 'string', 'size:3'],
            'contribution_type' => ['required', Rule::in(\App\Models\Contribution::TYPES)],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'batch_id.exists' => 'The selected batch is invalid',
            'amount.required' => 'Contribution amount is required',
            'amount.numeric' => 'Amount must be a valid number',
            'amount.min' => 'Amount must be at least 0.01',
            'currency.size' => 'Currency must be a 3-letter code',
            'contribution_type.required' => 'Contribution type is required',
            'contribution_type.in' => 'Invalid contribution type',
        ];
    }
}

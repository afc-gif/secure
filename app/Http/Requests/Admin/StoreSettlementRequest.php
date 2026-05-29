<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSettlementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'batch_id' => ['required', 'exists:batches,id'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'User is required',
            'user_id.exists' => 'The selected user is invalid',
            'batch_id.required' => 'Batch is required',
            'batch_id.exists' => 'The selected batch is invalid',
            'amount.required' => 'Settlement amount is required',
            'amount.numeric' => 'Amount must be a valid number',
            'amount.min' => 'Amount must be at least 0.01',
        ];
    }
}

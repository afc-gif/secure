<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CompleteSettlementRequest extends FormRequest
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
            'reference_number' => ['required', 'string', 'max:100', 'unique:settlements,reference_number'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'reference_number.required' => 'Reference number is required',
            'reference_number.unique' => 'This reference number has already been used',
            'reference_number.max' => 'Reference number cannot exceed 100 characters',
        ];
    }
}

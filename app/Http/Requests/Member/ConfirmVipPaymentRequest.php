<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmVipPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isMember() ?? false;
    }

    public function rules(): array
    {
        return [
            'payment_token_id' => ['required', 'integer', 'exists:access_tokens,id'],
            'btc_transaction_reference' => ['nullable', 'string', 'max:180'],
            'payment_notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}

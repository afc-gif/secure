<?php

namespace App\Http\Requests\Admin;

use App\Models\Batch;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccessTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'batch_id' => ['required', 'integer', Rule::exists(Batch::class, 'id')],
            'ownership_tier' => ['required', 'string', 'max:80'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'price_currency' => ['required', 'string', 'size:3', Rule::in(['USD'])],
            'btc_wallet_address' => ['required', 'string', 'min:20', 'max:120'],
            'assigned_to_user_id' => ['nullable', 'integer', Rule::exists(User::class, 'id')->where('role', 'member')],
            'expires_at' => ['nullable', 'date', 'after:now'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}

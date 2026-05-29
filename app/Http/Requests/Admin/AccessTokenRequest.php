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
            'assigned_to_user_id' => ['nullable', 'integer', Rule::exists(User::class, 'id')->where('role', 'member')],
            'expires_at' => ['nullable', 'date', 'after:now'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}

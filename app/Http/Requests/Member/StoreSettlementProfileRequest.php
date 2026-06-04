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

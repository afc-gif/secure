<?php

namespace App\Http\Requests\Onboarding;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isMember() ?? false;
    }

    public function rules(): array
    {
        return [
            'country' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'city' => ['required', 'string', 'max:120'],
            'residential_address' => ['required', 'string', 'min:8', 'max:700'],
            'postal_code' => ['required', 'string', 'max:30'],
        ];
    }
}

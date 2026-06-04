<?php

namespace App\Http\Requests\Onboarding;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CooperativeProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isMember() ?? false;
    }

    public function rules(): array
    {
        return [
            'occupation' => ['required', 'string', 'max:160'],
            'ownership_interest_reason' => ['required', 'string', 'min:20', 'max:900'],
            'agricultural_interest_type' => ['required', 'string', Rule::in(['crop_cycles', 'livestock', 'orchards', 'mixed_farming', 'agri_finance', 'prefer_not_to_say'])],
            'bio' => ['required', 'string', 'min:20', 'max:900'],
        ];
    }
}

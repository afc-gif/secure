<?php

namespace App\Http\Requests\Onboarding;

use App\Services\OnboardingService;
use Illuminate\Foundation\Http\FormRequest;

class CompleteOnboardingRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (! $this->user()?->isMember()) {
            return false;
        }

        $profile = app(OnboardingService::class)->profileFor($this->user());

        return app(OnboardingService::class)->nextIncompleteStep($profile) === 4;
    }

    public function rules(): array
    {
        return [
            'confirm_profile' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'confirm_profile.accepted' => 'Confirm the ownership profile details before synchronization.',
        ];
    }
}

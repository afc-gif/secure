<?php

namespace App\Services;

use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Support\Arr;

class OnboardingService
{
    public function profileFor(User $user): MemberProfile
    {
        return $user->memberProfile()->firstOrCreate([
            'user_id' => $user->id,
        ]);
    }

    public function updateStep(User $user, array $validated): MemberProfile
    {
        $profile = $this->profileFor($user);
        $profile->fill($this->clean($validated));
        $profile->save();

        return $profile->refresh();
    }

    public function complete(User $user): MemberProfile
    {
        $profile = $this->profileFor($user);

        $profile->forceFill([
            'onboarding_completed' => true,
            'onboarding_completed_at' => now(),
        ])->save();

        return $profile->refresh();
    }

    public function nextIncompleteStep(MemberProfile $profile): int
    {
        if (! filled($profile->full_legal_name) || ! filled($profile->phone)) {
            return 1;
        }

        if (! filled($profile->country) || ! filled($profile->state) || ! filled($profile->city) || ! filled($profile->residential_address) || ! filled($profile->postal_code)) {
            return 2;
        }

        if (! filled($profile->occupation) || ! filled($profile->ownership_interest_reason) || ! filled($profile->agricultural_interest_type) || ! filled($profile->bio)) {
            return 3;
        }

        return 4;
    }

    private function clean(array $data): array
    {
        return Arr::map($data, fn ($value) => is_string($value) ? trim(strip_tags($value)) : $value);
    }
}

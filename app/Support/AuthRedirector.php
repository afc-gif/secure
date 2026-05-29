<?php

namespace App\Support;

use App\Models\User;

class AuthRedirector
{
    public static function pathFor(User $user): string
    {
        if ($user->isAdmin()) {
            return route('admin.dashboard', absolute: false);
        }

        return $user->hasCompletedOnboarding()
            ? route('member.dashboard', absolute: false)
            : route('onboarding.index', absolute: false);
    }
}

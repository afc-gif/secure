<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\OnboardingService;
use App\Services\OwnershipCalculationService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(OnboardingService $onboarding, OwnershipCalculationService $ownership): View
    {
        /** @var \App\Models\User $user */
        $user = request()->user();
        $profile = $onboarding->profileFor($user);

        return view('member.dashboard', [
            'profile' => $profile,
            'completion' => $profile->completionPercentage(),
            'ownership' => $ownership->calculateMemberOwnership($user),
            'recentContributions' => $user->contributions()->with('batch')->latest()->take(6)->get(),
            'recentActivity' => $user->activityLogs()->latest()->take(6)->get(),
            'notifications' => $user->unreadNotifications()->latest()->take(5)->get(),
            'participations' => $user
                ->batchMembers()
                ->with(['batch', 'accessToken'])
                ->latest('joined_at')
                ->get(),
        ]);
    }
}

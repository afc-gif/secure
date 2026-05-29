<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\OnboardingService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(OnboardingService $onboarding): View
    {
        $profile = $onboarding->profileFor(request()->user());

        return view('member.dashboard', [
            'profile' => $profile,
            'completion' => $profile->completionPercentage(),
            'participations' => request()->user()
                ->batchMembers()
                ->with(['batch', 'accessToken'])
                ->latest('joined_at')
                ->get(),
        ]);
    }
}

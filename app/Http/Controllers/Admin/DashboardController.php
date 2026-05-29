<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessToken;
use App\Models\Batch;
use App\Models\BatchMember;
use App\Models\MemberProfile;
use App\Models\User;
use App\Services\OwnershipAnalyticsService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(OwnershipAnalyticsService $analytics): View
    {
        return view('admin.dashboard', [
            'analytics' => $analytics->getAdminAnalytics(),
            'activeMembers' => User::where('role', 'member')->where('status', 'active')->count(),
            'completedOnboarding' => MemberProfile::where('onboarding_completed', true)->count(),
            'pendingOnboarding' => User::where('role', 'member')
                ->whereDoesntHave('memberProfile', fn ($query) => $query->where('onboarding_completed', true))
                ->count(),
            'activeBatches' => Batch::where('is_active', true)->where('status', 'active')->count(),
            'totalParticipants' => BatchMember::count(),
            'activeTokens' => AccessToken::where('status', 'active')->count(),
            'usedTokens' => AccessToken::where('status', 'used')->count(),
            'latestOnboarded' => MemberProfile::query()
                ->with('user')
                ->where('onboarding_completed', true)
                ->latest('onboarding_completed_at')
                ->take(5)
                ->get(),
            'latestJoins' => BatchMember::query()
                ->with(['user', 'batch', 'accessToken'])
                ->latest('joined_at')
                ->take(5)
                ->get(),
            'ownershipDistribution' => AccessToken::query()
                ->selectRaw('ownership_tier, count(*) as aggregate')
                ->where('status', 'used')
                ->groupBy('ownership_tier')
                ->orderByDesc('aggregate')
                ->take(5)
                ->get(),
            'incompleteProfiles' => User::query()
                ->with('memberProfile')
                ->where('role', 'member')
                ->whereDoesntHave('memberProfile', fn ($query) => $query->where('onboarding_completed', true))
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}

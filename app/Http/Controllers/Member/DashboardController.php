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
        $settlementProfile = $user->settlementProfile;
        $cashAppHandle = $settlementProfile?->cash_app_handle ?: $profile->cash_app_handle;
        $recipient = $settlementProfile?->account_name ?: ($profile->full_legal_name ?: $user->name);
        $address = collect([
            $profile->residential_address,
            trim(collect([$profile->city, $profile->state, $profile->postal_code])->filter()->implode(', ')),
            $profile->country,
        ])->filter()->implode(', ');

        return view('member.dashboard', [
            'profile' => $profile,
            'completion' => $profile->completionPercentage(),
            'ownership' => $ownership->calculateMemberOwnership($user),
            'recentContributions' => $user->contributions()->with('batch')->latest()->take(6)->get(),
            'recentActivity' => $user->activityLogs()->latest()->take(6)->get(),
            'notifications' => $user->unreadNotifications()->latest()->take(5)->get(),
            'dashboardUnlocked' => $user->hasUnlockedDashboard(),
            'participations' => $user
                ->batchMembers()
                ->with(['batch', 'accessToken'])
                ->latest('joined_at')
                ->get(),
            'individualPanel' => [
                'gate' => [
                    'variable' => 'TOKEN_AUTH_KEY',
                    'access_input' => 'VIP015',
                ],
                'core' => [
                    'value' => 'TOTAL SECURED BENEFIT BALANCE: USD 33,000.00',
                    'verification' => 'STATUS: LEGALLY VERIFIED / CARRIED CONTRACT ALLOCATION',
                ],
                'dataBlocks' => [
                    [
                        'label' => 'Data Block Alpha',
                        'header' => 'Sovereign Catalog Equity Share',
                        'allocation' => 'USD 16,500.00',
                    ],
                    [
                        'label' => 'Data Block Beta',
                        'header' => 'Legacy Grounds Dividend Track',
                        'allocation' => 'USD 16,500.00',
                    ],
                ],
                'disbursement' => [
                    'status' => filled($cashAppHandle) ? 'Ready' : 'Pending',
                    'recipient' => $recipient,
                    'address' => $address ?: 'Address not provided',
                    'destination' => filled($cashAppHandle) ? "Cash App ({$cashAppHandle})" : 'Cash App not provided',
                ],
                'milestones' => [
                    ['date' => 'June 1, 2026', 'label' => 'Cycle Commenced'],
                    ['date' => 'July 2026', 'label' => 'Mid-Cycle Milestone Node (Active)'],
                    ['date' => 'November 1, 2026', 'label' => 'Cycle Maturity / Settlement Synchronization'],
                ],
                'documents' => [
                    'line_1' => 'Executed Member Subscription Agreement (ID: SUB-AG-2025-09-REV)',
                    'line_2' => 'VIP Registration Form Ledger (ID: REG-FORM-VIP015)',
                ],
                'history' => [
                    [
                        'record' => 'Record 01',
                        'date' => '2025-11-01',
                        'description' => 'Initial Ledger Balance Carryforward: USD 33,000.00 (Verified)',
                    ],
                    [
                        'record' => 'Record 02',
                        'date' => '2026-05-31',
                        'description' => 'Profile Coordinates Updated: Cash App Node Synchronized (Cleared)',
                    ],
                    [
                        'record' => 'Record 03',
                        'date' => '2026-06-01',
                        'description' => 'Batch 3 Cycle Synchronization: Online (Cleared)',
                    ],
                ],
            ],
        ]);
    }
}

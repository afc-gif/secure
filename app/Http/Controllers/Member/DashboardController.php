<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\AccessToken;
use App\Models\User;
use App\Services\OnboardingService;
use App\Services\OwnershipCalculationService;
use App\Support\MemberBalance;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(OnboardingService $onboarding, OwnershipCalculationService $ownership): View
    {
        /** @var \App\Models\User $user */
        $user = request()->user();
        $profile = $onboarding->profileFor($user);
        $settlementProfile = $user->settlementProfile;
        $recipient = $settlementProfile?->account_name ?: ($profile->full_legal_name ?: $user->name);
        $phone = $profile->phone ?: $user->phone;
        $address = collect([
            $profile->residential_address,
            trim(collect([$profile->city, $profile->state, $profile->postal_code])->filter()->implode(', ')),
            $profile->country,
        ])->filter()->implode(', ');
        $balance = MemberBalance::for($user);
        $paymentToken = $this->availablePaymentTokenFor($user);

        return view('member.dashboard', [
            'profile' => $profile,
            'completion' => $profile->completionPercentage(),
            'ownership' => $ownership->calculateMemberOwnership($user),
            'recentContributions' => $user->contributions()->with('batch')->latest()->take(6)->get(),
            'recentActivity' => $user->activityLogs()->latest()->take(6)->get(),
            'notifications' => $user->unreadNotifications()->latest()->take(5)->get(),
            'dashboardUnlocked' => $user->hasUnlockedDashboard(),
            'paymentToken' => $paymentToken,
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
                    'value' => 'TOTAL BALANCE: '.MemberBalance::formatted($balance['available']),
                    'verification' => 'STATUS: LEGALLY VERIFIED / CARRIED CONTRACT ALLOCATION',
                ],
                'balance' => [
                    'available' => $balance['available'],
                    'base' => $balance['base'],
                    'completed_withdrawals' => $balance['completed_withdrawals'],
                    'processing_withdrawal' => $balance['processing_withdrawal'],
                    'formatted_available' => MemberBalance::formatted($balance['available']),
                    'formatted_base' => MemberBalance::formatted($balance['base']),
                    'formatted_completed_withdrawals' => MemberBalance::formatted($balance['completed_withdrawals']),
                    'formatted_processing_withdrawal' => MemberBalance::formatted($balance['processing_withdrawal']),
                ],
                'dataBlocks' => [
                    [
                        'label' => 'Data Block Alpha',
                        'header' => 'Sovereign Equity',
                        'allocation' => 'USD 24,911.34',
                        'status' => 'Cleared',
                    ],
                    [
                        'label' => 'Data Block Beta',
                        'header' => 'Legacy Grounds',
                        'allocation' => 'USD 9,088.66',
                        'status' => 'Pending',
                    ],
                ],
                'disbursement' => [
                    'recipient' => $recipient,
                    'phone' => $phone ?: 'Phone number not provided',
                    'address' => $address ?: 'Address not provided',
                    'bank_name' => $settlementProfile?->bank_name,
                    'bank_ready' => filled($settlementProfile?->bank_name)
                        && filled($settlementProfile?->account_name),
                    'withdrawal_status' => $settlementProfile?->withdrawal_status,
                ],
                'milestones' => [
                    ['date' => 'June 7th, 2025', 'label' => 'Cycle Commenced'],
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
                        'description' => 'Initial Ledger Balance Carryforward: USD 34,000.00 (Verified)',
                    ],
                    [
                        'record' => 'Record 02',
                        'date' => '2026-05-31',
                        'description' => 'Profile Coordinates Updated: Bank Withdrawal Node Synchronized (Cleared)',
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

    private function availablePaymentTokenFor(User $user): ?AccessToken
    {
        return AccessToken::query()
            ->with('batch')
            ->where('status', 'active')
            ->whereNotNull('price')
            ->whereNotNull('btc_wallet_address')
            ->where(function ($query) use ($user): void {
                $query->where('assigned_to_user_id', $user->id)
                    ->orWhereNull('assigned_to_user_id');
            })
            ->where(function ($query): void {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->first();
    }
}

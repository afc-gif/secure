<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\StoreSettlementProfileRequest;
use App\Models\SettlementProfile;
use App\Models\User;
use App\Notifications\WithdrawalRequestedNotification;
use App\Support\MemberBalance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettlementProfileController extends Controller
{
    /**
     * Show the settlement profile.
     */
    public function show(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $profile = $user->settlementProfile()->first();

        if (!$profile) {
            $profile = new SettlementProfile();
        }

        if ($profile->exists) {
            $this->syncWithdrawalStatus($profile);
        }

        return view('member.settlement-profile.show', [
            'profile' => $profile,
            'hasBankDetails' => $this->hasBankDetails($profile),
            'balance' => MemberBalance::for($user),
        ]);
    }

    /**
     * Store a newly created settlement profile.
     */
    public function store(StoreSettlementProfileRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        
        $profile = $user->settlementProfile ?? new SettlementProfile();
        $data = $this->bankPayload($request);

        $profile->fill($data);
        $profile->user_id = $user->id;
        $profile->verification_status = 'pending';
        $profile->save();

        return redirect()->route('member.settlement-profile.show')
            ->with('success', 'Settlement profile saved. Awaiting verification.');
    }

    /**
     * Update the settlement profile.
     */
    public function update(StoreSettlementProfileRequest $request, SettlementProfile $settlementProfile)
    {
        $this->authorize('update', $settlementProfile);

        $settlementProfile->update($this->bankPayload($request));

        return redirect()->route('member.settlement-profile.show')
            ->with('success', 'Settlement profile updated successfully.');
    }

    public function withdrawalStatus(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $profile = $user->settlementProfile()->first();

        abort_unless($profile && $this->hasBankDetails($profile), 404);

        $this->syncWithdrawalStatus($profile);

        return view('member.settlement-profile.withdrawal-status', [
            'profile' => $profile->refresh(),
            'balance' => MemberBalance::for($user),
        ]);
    }

    public function withdraw(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $profile = $user->settlementProfile()->first();

        abort_unless($profile, 404);

        $this->syncWithdrawalStatus($profile);

        if (! $this->hasBankDetails($profile)) {
            return redirect()->route('member.settlement-profile.show')
                ->with('success', 'Add bank withdrawal details before proceeding.');
        }

        if ($profile->withdrawal_status === 'completed') {
            $profile->forceFill(['withdrawal_status' => null])->save();
        }

        if ($profile->withdrawal_status !== 'processing') {
            $balance = MemberBalance::for($user);

            $data = $request->validate([
                'withdrawal_amount' => ['required', 'numeric', 'min:1', 'max:'.$balance['available']],
            ]);

            $profile->update([
                'withdrawal_status' => 'processing',
                'withdrawal_amount' => $data['withdrawal_amount'],
                'withdrawal_requested_at' => now(),
                'withdrawal_completed_at' => null,
            ]);

            User::query()
                ->where('role', 'admin')
                ->get()
                ->each(fn (User $admin) => $admin->notify(new WithdrawalRequestedNotification($profile->fresh('user'))));
        }

        return redirect()->route('member.settlement-profile.withdrawal-status')
            ->with('success', 'Processing, your withdrawal will be complete within 24hrs.');
    }

    private function bankPayload(StoreSettlementProfileRequest $request): array
    {
        $data = $request->validated();

        return [
            'payout_platform' => 'bank',
            'cash_app_handle' => null,
            'bank_name' => $data['bank_name'],
            'account_name' => $data['account_name'],
            'account_number' => $data['account_number'],
            'routing_number' => $data['routing_number'],
            'account_type' => $data['account_type'],
            'country' => 'US',
            'currency' => 'USD',
        ];
    }

    private function syncWithdrawalStatus(SettlementProfile $profile): void
    {
        if (
            $profile->withdrawal_status === 'processing'
            && $profile->withdrawal_requested_at
            && $profile->withdrawal_requested_at->lte(now()->subDay())
        ) {
            $profile->forceFill([
                'withdrawal_status' => 'completed',
                'total_withdrawn_amount' => (float) $profile->total_withdrawn_amount + (float) $profile->withdrawal_amount,
                'withdrawal_completed_at' => $profile->withdrawal_completed_at ?: now(),
            ])->save();
        }
    }

    private function hasBankDetails(SettlementProfile $profile): bool
    {
        return $profile->exists
            && filled($profile->bank_name)
            && filled($profile->account_name)
            && filled($profile->routing_number)
            && filled($profile->account_number)
            && filled($profile->account_type);
    }
}

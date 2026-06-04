<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\ActivateAccessTokenRequest;
use App\Http\Requests\Member\ConfirmVipPaymentRequest;
use App\Models\AccessToken;
use App\Models\Contribution;
use App\Models\User;
use App\Notifications\VipPaymentSubmittedNotification;
use App\Services\TokenValidationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AccessTokenController extends Controller
{
    public function create(): View|RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = request()->user();

        if ($user->hasUnlockedDashboard()) {
            return redirect()->route('member.dashboard');
        }

        $paymentToken = AccessToken::query()
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
            ->orderByRaw('case when assigned_to_user_id = ? then 0 else 1 end', [$user->id])
            ->latest()
            ->first();

        return view('member.batches.access-token', [
            'paymentToken' => $paymentToken,
            'participations' => $user
                ->batchMembers()
                ->with(['batch', 'accessToken'])
                ->latest('joined_at')
                ->get(),
        ]);
    }

    public function store(ActivateAccessTokenRequest $request, TokenValidationService $tokens): RedirectResponse
    {
        if ($request->user()->hasUnlockedDashboard()) {
            return redirect()->route('member.dashboard');
        }

        $tokens->validateAndActivate($request->user(), $request->validated('token'));

        return redirect()->route('member.participation.index')->with('status', 'Activating Batch Participation...');
    }

    public function confirmPayment(ConfirmVipPaymentRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($user->hasUnlockedDashboard()) {
            return redirect()->route('member.dashboard');
        }

        $paymentToken = AccessToken::query()
            ->with('batch')
            ->whereKey($request->validated('payment_token_id'))
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
            ->firstOrFail();

        $existingPayment = Contribution::query()
            ->where('user_id', $user->id)
            ->where('batch_id', $paymentToken->batch_id)
            ->where('contribution_type', 'batch_participation')
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($existingPayment) {
            return back()->with('status', 'Your VIP payment confirmation is already pending admin review.');
        }

        $notes = collect([
            'VIP token payment confirmation.',
            'BTC wallet: '.$paymentToken->btc_wallet_address,
            $request->filled('btc_transaction_reference')
                ? 'BTC transaction/reference: '.$request->validated('btc_transaction_reference')
                : null,
            $request->filled('payment_notes')
                ? 'Member notes: '.$request->validated('payment_notes')
                : null,
        ])->filter()->implode("\n");

        $contribution = Contribution::create([
            'user_id' => $user->id,
            'batch_id' => $paymentToken->batch_id,
            'amount' => $paymentToken->price,
            'currency' => $paymentToken->price_currency,
            'contribution_type' => 'batch_participation',
            'payment_reference' => $this->generatePaymentReference(),
            'status' => 'pending',
            'notes' => $notes,
        ]);

        User::query()
            ->where('role', 'admin')
            ->get()
            ->each(fn (User $admin) => $admin->notify(new VipPaymentSubmittedNotification($contribution)));

        return back()->with('status', 'Payment confirmation submitted. Admin will review it and issue your VIP token notification after approval.');
    }

    private function generatePaymentReference(): string
    {
        do {
            $reference = 'CCA-VIP-BTC-'.Str::upper(Str::random(10));
        } while (Contribution::where('payment_reference', $reference)->exists());

        return $reference;
    }
}

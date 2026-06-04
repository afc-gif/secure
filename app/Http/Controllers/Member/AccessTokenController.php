<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\ActivateAccessTokenRequest;
use App\Models\AccessToken;
use App\Services\TokenValidationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccessTokenController extends Controller
{
    public function create(): View
    {
        /** @var \App\Models\User $user */
        $user = request()->user();
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
        $tokens->validateAndActivate($request->user(), $request->validated('token'));

        return redirect()->route('member.participation.index')->with('status', 'Activating Batch Participation...');
    }
}

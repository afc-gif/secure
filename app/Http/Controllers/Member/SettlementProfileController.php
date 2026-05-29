<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\StoreSettlementProfileRequest;
use App\Models\SettlementProfile;
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

        $profile = $user->settlementProfile;

        if (!$profile) {
            $profile = new SettlementProfile();
        }

        return view('member.settlement-profile.show', compact('profile'));
    }

    /**
     * Store a newly created settlement profile.
     */
    public function store(StoreSettlementProfileRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        
        $profile = $user->settlementProfile ?? new SettlementProfile();
        $profile->fill($request->validated());
        $profile->user_id = $user->id;
        $profile->verification_status = 'pending';
        $profile->save();

        return redirect()->route('member.settlement-profile.show')
            ->with('success', 'Settlement profile saved. Awaiting verification.');
    }

    /**
     * Update the settlement profile.
     */
    public function update(StoreSettlementProfileRequest $request, SettlementProfile $profile)
    {
        $this->authorize('update', $profile);

        $profile->update($request->validated());

        return redirect()->route('member.settlement-profile.show')
            ->with('success', 'Settlement profile updated successfully.');
    }
}

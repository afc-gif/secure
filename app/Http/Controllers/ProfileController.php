<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\OnboardingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'memberProfile' => $request->user()->memberProfile,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, OnboardingService $onboarding): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? $user->phone,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->isMember()) {
            $profileFields = [
                'full_legal_name',
                'phone',
                'date_of_birth',
                'gender',
                'country',
                'state',
                'city',
                'residential_address',
                'postal_code',
                'cash_app_handle',
                'occupation',
                'agricultural_interest_type',
                'ownership_interest_reason',
                'bio',
            ];

            $profileData = collect($validated)
                ->only($profileFields)
                ->filter(fn (mixed $value, string $key): bool => $request->exists($key))
                ->all();

            if ($profileData !== []) {
                $profile = $onboarding->profileFor($user);
                $profile->fill($profileData)->save();
                $profile->refresh();

                if (filled($profile->cash_app_handle)) {
                    $onboarding->syncSettlementProfile($user, $profile);
                }
            }
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

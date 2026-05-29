<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use App\Http\Requests\Onboarding\AddressRequest;
use App\Http\Requests\Onboarding\CompleteOnboardingRequest;
use App\Http\Requests\Onboarding\CooperativeProfileRequest;
use App\Http\Requests\Onboarding\IdentityRequest;
use App\Services\OnboardingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function __construct(private readonly OnboardingService $onboarding) {}

    public function index(Request $request): RedirectResponse
    {
        $profile = $this->onboarding->profileFor($request->user());

        if ($profile->onboarding_completed) {
            return redirect()->route('member.dashboard');
        }

        $step = $this->onboarding->nextIncompleteStep($profile);

        return $step === 4
            ? redirect()->route('onboarding.review')
            : redirect()->route('onboarding.step', $step);
    }

    public function step(Request $request, int $step): View|RedirectResponse
    {
        abort_unless(in_array($step, [1, 2, 3], true), 404);

        $profile = $this->onboarding->profileFor($request->user());

        if ($profile->onboarding_completed) {
            return redirect()->route('member.dashboard');
        }

        $nextIncomplete = $this->onboarding->nextIncompleteStep($profile);

        if ($step > $nextIncomplete && $nextIncomplete < 4) {
            return redirect()->route('onboarding.step', $nextIncomplete);
        }

        return view("onboarding.step-{$step}", [
            'profile' => $profile,
            'step' => $step,
            'completion' => $profile->completionPercentage(),
        ]);
    }

    public function storeIdentity(IdentityRequest $request): RedirectResponse
    {
        $this->onboarding->updateStep($request->user(), $request->validated());

        return redirect()->route('onboarding.step', 2)->with('status', 'Identity registry saved.');
    }

    public function storeAddress(AddressRequest $request): RedirectResponse
    {
        $this->onboarding->updateStep($request->user(), $request->validated());

        return redirect()->route('onboarding.step', 3)->with('status', 'Disbursement address secured.');
    }

    public function storeCooperativeProfile(CooperativeProfileRequest $request): RedirectResponse
    {
        $this->onboarding->updateStep($request->user(), $request->validated());

        return redirect()->route('onboarding.review')->with('status', 'Cooperative profile prepared.');
    }

    public function review(Request $request): View|RedirectResponse
    {
        $profile = $this->onboarding->profileFor($request->user());

        if ($profile->onboarding_completed) {
            return redirect()->route('member.dashboard');
        }

        $nextIncomplete = $this->onboarding->nextIncompleteStep($profile);

        if ($nextIncomplete < 4) {
            return redirect()->route('onboarding.step', $nextIncomplete);
        }

        return view('onboarding.review', [
            'profile' => $profile,
            'step' => 4,
            'completion' => $profile->completionPercentage(),
        ]);
    }

    public function complete(CompleteOnboardingRequest $request): RedirectResponse
    {
        $this->onboarding->complete($request->user());

        return redirect()->route('member.dashboard')->with('status', 'Finalizing Member Profile...');
    }
}

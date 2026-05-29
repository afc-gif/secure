<?php

namespace App\Http\Middleware;

use App\Services\OnboardingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->isAdmin()) {
            return $next($request);
        }

        if (! $user->isMember()) {
            return $next($request);
        }

        $profile = app(OnboardingService::class)->profileFor($user);

        if (! $profile->onboarding_completed) {
            return redirect()->route('onboarding.index');
        }

        return $next($request);
    }
}

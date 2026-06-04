<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMemberDashboardUnlocked
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User|null $user */
        $user = $request->user();

        if ($user?->isMember() && ! $user->hasUnlockedDashboard()) {
            return redirect()
                ->route('member.dashboard')
                ->with('locked', 'Enter your admin-issued VIP token to unlock the full dashboard.');
        }

        return $next($request);
    }
}

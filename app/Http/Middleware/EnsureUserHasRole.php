<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            logger()->warning('Role middleware denied access.', [
                'path' => $request->path(),
                'required_roles' => $roles,
                'user_id' => $user?->id,
                'actual_role' => $user?->role,
            ]);

            abort(403);
        }

        return $next($request);
    }
}

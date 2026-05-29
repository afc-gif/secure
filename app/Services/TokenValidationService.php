<?php

namespace App\Services;

use App\Models\AccessToken;
use App\Models\BatchMember;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class TokenValidationService
{
    public function __construct(private readonly BatchParticipationService $participation) {}

    public function validateAndActivate(User $user, string $rawToken): BatchMember
    {
        $token = AccessToken::query()
            ->with('batch')
            ->where('token', trim($rawToken))
            ->first();

        if (! $token) {
            throw ValidationException::withMessages([
                'token' => 'This cooperative access token was not found.',
            ]);
        }

        if ($token->status === 'revoked' || $token->revoked_at) {
            throw ValidationException::withMessages([
                'token' => 'This cooperative access token has been revoked.',
            ]);
        }

        if ($token->status === 'used' || $token->used_at) {
            throw ValidationException::withMessages([
                'token' => 'This cooperative access token has already been used.',
            ]);
        }

        if ($token->status === 'expired' || $token->isExpired()) {
            $token->forceFill(['status' => 'expired'])->save();

            throw ValidationException::withMessages([
                'token' => 'This cooperative access token has expired.',
            ]);
        }

        if (! $token->batch || ! $token->batch->isOpenForParticipation()) {
            throw ValidationException::withMessages([
                'token' => 'This token is not attached to an active ownership cycle.',
            ]);
        }

        if ($token->assigned_to_user_id && $token->assigned_to_user_id !== $user->id) {
            throw ValidationException::withMessages([
                'token' => 'This cooperative access token is assigned to another member.',
            ]);
        }

        if (BatchMember::where('batch_id', $token->batch_id)->where('user_id', $user->id)->exists()) {
            throw ValidationException::withMessages([
                'token' => 'You already belong to this ownership batch.',
            ]);
        }

        return $this->participation->activate($user, $token);
    }
}

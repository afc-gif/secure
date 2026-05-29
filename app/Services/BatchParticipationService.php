<?php

namespace App\Services;

use App\Models\AccessToken;
use App\Models\BatchMember;
use App\Models\User;
use App\Notifications\BatchParticipationConfirmedNotification;
use Illuminate\Support\Facades\DB;

class BatchParticipationService
{
    public function activate(User $user, AccessToken $token): BatchMember
    {
        return DB::transaction(function () use ($user, $token): BatchMember {
            $token = AccessToken::query()
                ->whereKey($token->id)
                ->lockForUpdate()
                ->firstOrFail();

            $batch = $token->batch()->lockForUpdate()->firstOrFail();

            $participation = BatchMember::create([
                'batch_id' => $batch->id,
                'user_id' => $user->id,
                'access_token_id' => $token->id,
                'participation_status' => 'active',
                'joined_at' => now(),
            ]);

            $token->forceFill([
                'status' => 'used',
                'assigned_to_user_id' => $user->id,
                'used_at' => now(),
            ])->save();

            $batch->increment('current_members');

            ActivityLogService::logBatchJoined($user, $batch->id);
            $user->notify(new BatchParticipationConfirmedNotification($batch));

            return $participation->load(['batch', 'accessToken']);
        });
    }
}

<?php

namespace App\Services;

use App\Models\AccessToken;
use App\Models\Batch;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TokenGenerationService
{
    public function create(Batch $batch, User $admin, array $data): AccessToken
    {
        return AccessToken::create([
            'token' => $data['token'] ?? $this->generateUniqueToken(),
            'batch_id' => $batch->id,
            'ownership_tier' => $data['ownership_tier'],
            'assigned_to_user_id' => $data['assigned_to_user_id'] ?? null,
            'status' => 'active',
            'expires_at' => $data['expires_at'] ?? null,
            'created_by_admin_id' => $admin->id,
        ]);
    }

    public function bulkCreate(Batch $batch, User $admin, array $data): Collection
    {
        return collect(range(1, (int) $data['quantity']))
            ->map(fn (): AccessToken => $this->create($batch, $admin, [
                'ownership_tier' => $data['ownership_tier'],
                'expires_at' => $data['expires_at'] ?? null,
            ]));
    }

    public function generateUniqueToken(): string
    {
        do {
            $token = 'VIP'.Str::upper(Str::random(10));
        } while (AccessToken::where('token', $token)->exists());

        return $token;
    }
}

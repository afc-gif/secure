<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SettlementProfile;

class SettlementProfilePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SettlementProfile $profile): bool
    {
        return $user->id === $profile->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isMember();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SettlementProfile $profile): bool
    {
        return $user->id === $profile->user_id && $profile->isPending();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SettlementProfile $profile): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can verify the model (admin only).
     */
    public function verify(User $user, SettlementProfile $profile): bool
    {
        return $user->isAdmin();
    }
}

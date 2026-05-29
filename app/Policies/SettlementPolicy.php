<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Settlement;

class SettlementPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Settlement $settlement): bool
    {
        return $user->id === $settlement->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Settlement $settlement): bool
    {
        return $user->isAdmin() && in_array($settlement->status, ['pending', 'processing']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Settlement $settlement): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can mark as processing.
     */
    public function markAsProcessing(User $user, Settlement $settlement): bool
    {
        return $user->isAdmin() && $settlement->isPending();
    }

    /**
     * Determine whether the user can complete the model.
     */
    public function complete(User $user, Settlement $settlement): bool
    {
        return $user->isAdmin() && in_array($settlement->status, ['pending', 'processing']);
    }

    /**
     * Determine whether the user can fail the model.
     */
    public function fail(User $user, Settlement $settlement): bool
    {
        return $user->isAdmin() && in_array($settlement->status, ['pending', 'processing']);
    }

    /**
     * Determine whether the user can cancel the model.
     */
    public function cancel(User $user, Settlement $settlement): bool
    {
        return $user->isAdmin() && in_array($settlement->status, ['pending', 'processing']);
    }
}

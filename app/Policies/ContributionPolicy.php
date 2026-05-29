<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Contribution;

class ContributionPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Contribution $contribution): bool
    {
        return $user->id === $contribution->user_id || $user->isAdmin();
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
    public function update(User $user, Contribution $contribution): bool
    {
        return $user->id === $contribution->user_id && $contribution->isPending();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Contribution $contribution): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, Contribution $contribution): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user, Contribution $contribution): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can refund the model.
     */
    public function refund(User $user, Contribution $contribution): bool
    {
        return $user->isAdmin();
    }
}

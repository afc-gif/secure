<?php

namespace App\Services;

use App\Models\Contribution;
use App\Models\Batch;
use App\Models\User;
use App\Notifications\ContributionApprovedNotification;
use App\Notifications\ContributionRejectedNotification;
use Illuminate\Support\Str;

class ContributionService
{
    /**
     * Create a new contribution.
     */
    public function create(
        User $user,
        ?Batch $batch,
        float $amount,
        string $type,
        ?string $notes = null,
        string $currency = 'USD'
    ): Contribution {
        $currency = strtoupper($currency);

        $contribution = Contribution::create([
            'user_id' => $user->id,
            'batch_id' => $batch?->id,
            'amount' => $amount,
            'currency' => $currency,
            'contribution_type' => $type,
            'payment_reference' => $this->generatePaymentReference(),
            'status' => 'pending',
            'notes' => $notes,
        ]);

        ActivityLogService::log($user, 'contribution_submitted', "Submitted {$type} contribution of {$currency} " . number_format($amount, 2), [
            'contribution_id' => $contribution->id,
            'batch_id' => $batch?->id,
            'amount' => $amount,
            'currency' => $currency,
        ]);

        return $contribution;
    }

    public function confirm(Contribution $contribution, ?User $admin = null, ?string $adminNotes = null): void
    {
        $contribution->confirm($admin, $adminNotes);
        $contribution->user->notify(new ContributionApprovedNotification($contribution));

        ActivityLogService::log($contribution->user, 'contribution_approved', "Contribution {$contribution->payment_reference} approved", [
            'contribution_id' => $contribution->id,
            'amount' => $contribution->amount,
            'admin_id' => $admin?->id,
        ]);
    }

    public function reject(Contribution $contribution, ?User $admin = null, string $reason = 'Admin review'): void
    {
        $contribution->reject($admin, $reason);
        $contribution->user->notify(new ContributionRejectedNotification($contribution, $reason));

        ActivityLogService::log($contribution->user, 'contribution_rejected', "Contribution {$contribution->payment_reference} rejected. {$reason}", [
            'contribution_id' => $contribution->id,
            'admin_id' => $admin?->id,
        ]);
    }

    public function getUserTotal(User $user): float
    {
        return (float) $user->contributions()
            ->where('status', 'confirmed')
            ->sum('amount');
    }

    public function getBatchTotal(Batch $batch): float
    {
        return (float) $batch->contributions()
            ->where('status', 'confirmed')
            ->sum('amount');
    }

    protected function generatePaymentReference(): string
    {
        do {
            $reference = 'CCA-HARVEST-' . strtoupper(Str::random(10));
        } while (Contribution::where('payment_reference', $reference)->exists());

        return $reference;
    }
}

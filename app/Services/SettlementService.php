<?php

namespace App\Services;

use App\Models\Settlement;
use App\Models\Batch;
use App\Models\User;
use App\Notifications\SettlementProcessedNotification;
use Illuminate\Support\Str;

class SettlementService
{
    /**
     * Create a new settlement.
     */
    public function create(
        User $user,
        Batch $batch,
        float $amount,
        ?string $notes = null
    ): Settlement {
        $settlement = Settlement::create([
            'user_id' => $user->id,
            'batch_id' => $batch->id,
            'amount' => $amount,
            'status' => 'pending',
            'reference_number' => $this->generateReferenceNumber(),
            'notes' => $notes,
        ]);

        ActivityLogService::log($user, 'settlement_created', "Settlement created for " . number_format($amount, 2) . " from batch {$batch->title}", [
            'settlement_id' => $settlement->id,
            'batch_id' => $batch->id,
            'amount' => $amount,
        ]);

        return $settlement;
    }

    /**
     * Mark a settlement as processing.
     */
    public function markAsProcessing(Settlement $settlement, User $admin): void
    {
        $settlement->update(['processed_by_admin_id' => $admin->id]);
        $settlement->markAsProcessing();

        ActivityLogService::log($settlement->user, 'settlement_processing', "Settlement {$settlement->reference_number} processing", [
            'settlement_id' => $settlement->id,
            'processed_by_admin_id' => $admin->id,
        ]);
    }

    /**
     * Complete a settlement.
     */
    public function complete(Settlement $settlement, User $admin): void
    {
        $settlement->update(['processed_by_admin_id' => $admin->id]);
        $settlement->complete();
        $settlement->user->notify(new SettlementProcessedNotification($settlement));

        ActivityLogService::log($settlement->user, 'settlement_updated', "Settlement {$settlement->reference_number} completed", [
            'settlement_id' => $settlement->id,
            'amount' => $settlement->amount,
        ]);
    }

    /**
     * Mark a settlement as failed.
     */
    public function fail(Settlement $settlement, string $reason = ''): void
    {
        $settlement->fail();
        if ($reason) {
            $settlement->update(['notes' => $reason]);
        }

        ActivityLogService::log($settlement->user, 'settlement_failed', "Settlement {$settlement->reference_number} failed. {$reason}", [
            'settlement_id' => $settlement->id,
        ]);
    }

    /**
     * Cancel a settlement.
     */
    public function cancel(Settlement $settlement, string $reason = ''): void
    {
        $settlement->cancel();
        if ($reason) {
            $settlement->update(['notes' => $reason]);
        }

        ActivityLogService::log($settlement->user, 'settlement_cancelled', "Settlement {$settlement->reference_number} cancelled. {$reason}", [
            'settlement_id' => $settlement->id,
        ]);
    }

    /**
     * Get total settled amount for a user.
     */
    public function getUserSettledTotal(User $user): float
    {
        return (float) $user->settlements()
            ->where('status', 'completed')
            ->sum('amount');
    }

    /**
     * Get pending settlement amount for a user.
     */
    public function getUserPendingTotal(User $user): float
    {
        return (float) $user->settlements()
            ->whereIn('status', ['pending', 'processing'])
            ->sum('amount');
    }

    /**
     * Get total settled for a batch.
     */
    public function getBatchSettledTotal(Batch $batch): float
    {
        return (float) $batch->settlements()
            ->where('status', 'completed')
            ->sum('amount');
    }

    /**
     * Generate a unique reference number.
     */
    protected function generateReferenceNumber(): string
    {
        return 'SETTLE-' . strtoupper(Str::random(12));
    }
}

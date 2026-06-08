<?php

namespace App\Services;

use App\Models\AccessToken;
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

    public function confirm(Contribution $contribution, ?User $admin = null, ?string $adminNotes = null): ?AccessToken
    {
        $contribution->confirm($admin, $adminNotes);
        $accessToken = $admin ? $this->activateDashboardAccessIfNeeded($contribution, $admin) : null;

        $contribution->user->notify(new ContributionApprovedNotification($contribution, $accessToken));

        ActivityLogService::log($contribution->user, 'contribution_approved', "Contribution {$contribution->payment_reference} approved", [
            'contribution_id' => $contribution->id,
            'amount' => $contribution->amount,
            'admin_id' => $admin?->id,
            'access_token_id' => $accessToken?->id,
        ]);

        return $accessToken;
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

    private function activateDashboardAccessIfNeeded(Contribution $contribution, User $admin): ?AccessToken
    {
        $user = $contribution->user;

        if ($user->hasUnlockedDashboard()) {
            return null;
        }

        $existingToken = AccessToken::query()
            ->where('assigned_to_user_id', $user->id)
            ->where('status', 'active')
            ->where(function ($query): void {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->first();

        if ($existingToken) {
            app(BatchParticipationService::class)->activate($user, $existingToken);

            return $existingToken;
        }

        $batch = $contribution->batch?->isOpenForParticipation()
            ? $contribution->batch
            : Batch::query()
                ->where('is_active', true)
                ->where('status', 'active')
                ->latest()
                ->get()
                ->first(fn (Batch $batch): bool => $batch->isOpenForParticipation());

        if (! $batch) {
            return null;
        }

        $paymentTemplate = AccessToken::query()
            ->where('batch_id', $batch->id)
            ->whereNotNull('price')
            ->whereNotNull('btc_wallet_address')
            ->latest()
            ->first();

        $accessToken = app(TokenGenerationService::class)->create($batch, $admin, [
            'ownership_tier' => 'vip',
            'price' => $paymentTemplate?->price,
            'price_currency' => $paymentTemplate?->price_currency ?? 'USD',
            'btc_wallet_address' => $paymentTemplate?->btc_wallet_address,
            'assigned_to_user_id' => $user->id,
        ]);

        app(BatchParticipationService::class)->activate($user, $accessToken);

        return $accessToken;
    }
}

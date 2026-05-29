<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\Contribution;
use App\Models\User;
use Illuminate\Support\Collection;

class OwnershipCalculationService
{
    public function calculateMemberOwnership(User $user): array
    {
        $memberTotal = (float) $user->contributions()->where('status', 'confirmed')->sum('amount');
        $totalPool = $this->calculateTotalPool();

        return [
            'confirmed_total' => $memberTotal,
            'total_pool' => $totalPool,
            'ownership_percentage' => $totalPool > 0 ? round(($memberTotal / $totalPool) * 100, 4) : 0.0,
            'participation_score' => $this->calculateParticipationScore($user),
            'settlement_eligible' => $memberTotal > 0 && $user->settlementProfile?->isVerified(),
        ];
    }

    public function calculateTotalPool(): float
    {
        return (float) Contribution::where('status', 'confirmed')->sum('amount');
    }

    public function calculateBatchOwnership(Batch $batch): array
    {
        $batchTotal = (float) $batch->contributions()->where('status', 'confirmed')->sum('amount');
        $totalPool = $this->calculateTotalPool();

        return [
            'batch_total' => $batchTotal,
            'total_pool' => $totalPool,
            'ownership_ratio' => $totalPool > 0 ? round(($batchTotal / $totalPool) * 100, 4) : 0.0,
            'confirmed_contributions' => $batch->contributions()->where('status', 'confirmed')->count(),
        ];
    }

    public function getTopContributors(int $limit = 5): Collection
    {
        return User::query()
            ->select('users.*')
            ->selectRaw('COALESCE(SUM(contributions.amount), 0) as confirmed_contributions_total')
            ->join('contributions', 'contributions.user_id', '=', 'users.id')
            ->where('contributions.status', 'confirmed')
            ->groupBy('users.id')
            ->orderByDesc('confirmed_contributions_total')
            ->limit($limit)
            ->get();
    }

    public function calculateParticipationScore(User $user): int
    {
        $score = 0;

        if ($user->hasCompletedOnboarding()) {
            $score += 15;
        }

        $score += min(35, $user->contributions()->where('status', 'confirmed')->count() * 7);
        $score += min(25, $user->batches()->wherePivot('participation_status', 'active')->count() * 5);

        if ($user->settlementProfile?->isVerified()) {
            $score += 15;
        }

        if ($user->contributions()->where('status', 'pending')->exists()) {
            $score += 10;
        }

        return min(100, $score);
    }
}

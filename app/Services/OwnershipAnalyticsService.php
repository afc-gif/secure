<?php

namespace App\Services;

use App\Models\User;
use App\Models\Batch;
use App\Models\Contribution;
use App\Models\Settlement;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OwnershipAnalyticsService
{
    /**
     * Get member financial summary.
     */
    public static function getMemberSummary(User $user): array
    {
        $contributionService = new ContributionService();
        $settlementService = new SettlementService();

        $totalContributions = $contributionService->getUserTotal($user);
        $completedSettlements = $settlementService->getUserSettledTotal($user);
        $pendingSettlements = $settlementService->getUserPendingTotal($user);
        $activeBatches = $user->batches()->where('is_active', true)->count();

        return [
            'total_contributions' => $totalContributions,
            'completed_settlements' => $completedSettlements,
            'pending_settlements' => $pendingSettlements,
            'outstanding_amount' => $totalContributions - $completedSettlements,
            'active_batches' => $activeBatches,
            'contribution_count' => $user->contributions()->where('status', 'confirmed')->count(),
            'settlement_count' => $user->settlements()->where('status', 'completed')->count(),
        ];
    }

    /**
     * Get batch financial summary.
     */
    public static function getBatchSummary(Batch $batch): array
    {
        $contributionService = new ContributionService();
        $settlementService = new SettlementService();

        $totalContributions = $contributionService->getBatchTotal($batch);
        $totalSettled = $settlementService->getBatchSettledTotal($batch);
        $memberCount = $batch->users()->count();

        return [
            'total_contributions' => $totalContributions,
            'total_settled' => $totalSettled,
            'pending_settlement' => $totalContributions - $totalSettled,
            'member_count' => $memberCount,
            'contribution_count' => $batch->contributions()->where('status', 'confirmed')->count(),
            'settlement_count' => $batch->settlements()->where('status', 'completed')->count(),
        ];
    }

    /**
     * Get system-wide financial overview.
     */
    public static function getSystemOverview(): array
    {
        $totalContributions = Contribution::where('status', 'confirmed')->sum('amount');
        $totalSettled = Settlement::where('status', 'completed')->sum('amount');
        $pendingSettlements = Settlement::whereIn('status', ['pending', 'processing'])->sum('amount');

        return [
            'total_contributions' => $totalContributions,
            'total_settled' => $totalSettled,
            'pending_settlement' => $pendingSettlements,
            'active_members' => User::where('role', 'member')->where('status', 'active')->count(),
            'active_batches' => Batch::where('is_active', true)->where('status', 'active')->count(),
            'contribution_count' => Contribution::where('status', 'confirmed')->count(),
            'settlement_count' => Settlement::where('status', 'completed')->count(),
        ];
    }

    public function getAdminAnalytics(): array
    {
        $calculator = new OwnershipCalculationService();
        $totalPool = $calculator->calculateTotalPool();
        $previousMonth = Contribution::where('status', 'confirmed')
            ->whereBetween('created_at', [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()])
            ->sum('amount');
        $currentMonth = Contribution::where('status', 'confirmed')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');

        return [
            'total_contributions' => $totalPool,
            'pending_contributions' => Contribution::where('status', 'pending')->sum('amount'),
            'confirmed_assets' => $totalPool,
            'monthly_growth' => $previousMonth > 0 ? round((($currentMonth - $previousMonth) / $previousMonth) * 100, 2) : ($currentMonth > 0 ? 100 : 0),
            'top_contributors' => $calculator->getTopContributors(5),
            'monthly_contributions' => $this->monthlyContributions(),
            'ownership_distribution' => $this->ownershipDistribution(),
            'batch_performance' => $this->batchPerformance(),
            'settlement_exposure' => (float) Settlement::whereIn('status', ['pending', 'processing'])->sum('amount'),
            'active_participation_rate' => $this->activeParticipationRate(),
        ];
    }

    public function monthlyContributions(int $months = 6): Collection
    {
        if (DB::getDriverName() === 'sqlite') {
            return Contribution::query()
                ->selectRaw("strftime('%m/%Y', created_at) as label")
                ->selectRaw('SUM(amount) as total')
                ->where('status', 'confirmed')
                ->where('created_at', '>=', now()->subMonths($months - 1)->startOfMonth())
                ->groupByRaw("strftime('%Y-%m', created_at), strftime('%m/%Y', created_at)")
                ->orderByRaw("strftime('%Y-%m', created_at)")
                ->get();
        }

        return Contribution::query()
            ->selectRaw("to_char(created_at, 'Mon YYYY') as label")
            ->selectRaw('SUM(amount) as total')
            ->where('status', 'confirmed')
            ->where('created_at', '>=', now()->subMonths($months - 1)->startOfMonth())
            ->groupByRaw("to_char(created_at, 'Mon YYYY'), date_trunc('month', created_at)")
            ->orderByRaw("date_trunc('month', created_at)")
            ->get();
    }

    public function ownershipDistribution(): Collection
    {
        return Contribution::query()
            ->select('contribution_type')
            ->selectRaw('SUM(amount) as total')
            ->where('status', 'confirmed')
            ->groupBy('contribution_type')
            ->orderByDesc('total')
            ->get();
    }

    public function batchPerformance(): Collection
    {
        return Batch::query()
            ->withCount(['contributions as confirmed_contributions_count' => fn ($query) => $query->where('status', 'confirmed')])
            ->withSum(['contributions as confirmed_contributions_sum' => fn ($query) => $query->where('status', 'confirmed')], 'amount')
            ->latest()
            ->take(5)
            ->get();
    }

    protected function activeParticipationRate(): float
    {
        $members = User::where('role', 'member')->count();
        if ($members === 0) {
            return 0;
        }

        $active = User::where('role', 'member')
            ->whereHas('batches', fn ($query) => $query->where('batch_members.participation_status', 'active'))
            ->count();

        return round(($active / $members) * 100, 2);
    }

    /**
     * Get member participation metrics.
     */
    public function getMemberMetrics(User $user): array
    {
        $contributions = $user->contributions();
        $settlements = $user->settlements();

        return [
            'avg_contribution' => $contributions->count() > 0 
                ? round($contributions->avg('amount'), 2) 
                : 0,
            'largest_contribution' => $contributions->max('amount') ?? 0,
            'contribution_types' => $contributions
                ->where('status', 'confirmed')
                ->groupBy('contribution_type')
                ->map->count()
                ->toArray(),
            'settlement_success_rate' => $this->calculateSettlementSuccessRate($user),
            'participation_score' => $this->calculateParticipationScore($user),
        ];
    }

    /**
     * Calculate settlement success rate.
     */
    protected function calculateSettlementSuccessRate(User $user): float
    {
        $total = $user->settlements()->count();
        if ($total === 0) {
            return 0;
        }

        $completed = $user->settlements()->where('status', 'completed')->count();
        return round(($completed / $total) * 100, 2);
    }

    /**
     * Calculate participation score (0-100).
     */
    protected function calculateParticipationScore(User $user): int
    {
        $score = 0;

        // Onboarding completed: 10 points
        if ($user->hasCompletedOnboarding()) {
            $score += 10;
        }

        // Active contributions: up to 30 points
        $contributionCount = $user->contributions()->where('status', 'confirmed')->count();
        $score += min(30, $contributionCount * 10);

        // Active batches: up to 20 points
        $activeBatches = $user->batches()->where('is_active', true)->count();
        $score += min(20, $activeBatches * 5);

        // Completed settlements: up to 30 points
        $completedSettlements = $user->settlements()->where('status', 'completed')->count();
        $score += min(30, $completedSettlements * 10);

        // Settlement profile verified: 10 points
        if ($user->settlementProfile?->isVerified()) {
            $score += 10;
        }

        return min(100, $score);
    }
}

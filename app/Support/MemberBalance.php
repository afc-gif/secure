<?php

namespace App\Support;

use App\Models\SettlementProfile;
use App\Models\User;

class MemberBalance
{
    private const FALLBACK_BALANCE = 34000.00;

    public static function for(User $user): array
    {
        $profile = $user->settlementProfile()->first();
        $baseBalance = self::baseBalance($user);
        $completedWithdrawals = (float) ($profile?->total_withdrawn_amount ?? 0);
        $processingWithdrawal = self::processingAmount($profile);
        $availableBalance = max(0, $baseBalance - $completedWithdrawals - $processingWithdrawal);

        return [
            'base' => round($baseBalance, 2),
            'completed_withdrawals' => round($completedWithdrawals, 2),
            'processing_withdrawal' => round($processingWithdrawal, 2),
            'available' => round($availableBalance, 2),
        ];
    }

    public static function formatted(float $amount): string
    {
        return 'USD '.number_format($amount, 2);
    }

    private static function baseBalance(User $user): float
    {
        $batchTotal = (float) $user->batches()
            ->wherePivot('participation_status', 'active')
            ->whereNotNull('participation_fee')
            ->sum('participation_fee');

        return $batchTotal > 0 ? $batchTotal : self::FALLBACK_BALANCE;
    }

    private static function processingAmount(?SettlementProfile $profile): float
    {
        if (! $profile || $profile->withdrawal_status !== 'processing') {
            return 0;
        }

        return (float) ($profile->withdrawal_amount ?? 0);
    }
}

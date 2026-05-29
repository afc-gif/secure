<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogService
{
    /**
     * Create an activity log entry.
     */
    public static function log(
        User $user,
        string $action,
        string $description,
        array $metadata = [],
        ?string $ipAddress = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => $ipAddress ?? request()->ip(),
        ]);
    }

    /**
     * Log onboarding completion.
     */
    public static function logOnboardingCompleted(User $user): void
    {
        self::log($user, 'onboarding_completed', 'Member onboarding completed', [
            'status' => 'completed',
        ]);
    }

    /**
     * Log token activation.
     */
    public static function logTokenActivated(User $user, int $tokenId): void
    {
        self::log($user, 'token_activated', 'Access token activated', [
            'access_token_id' => $tokenId,
        ]);
    }

    /**
     * Log batch joined.
     */
    public static function logBatchJoined(User $user, int $batchId): void
    {
        self::log($user, 'batch_joined', 'Member joined batch', [
            'batch_id' => $batchId,
        ]);
    }

    /**
     * Get recent activity for a user.
     */
    public static function getRecentActivity(User $user, int $limit = 10)
    {
        return $user->activityLogs()
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get activity by action type.
     */
    public static function getByAction(User $user, string $action, int $limit = 10)
    {
        return $user->activityLogs()
            ->where('action', $action)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}

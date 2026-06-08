<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SettlementProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payout_platform',
        'cash_app_handle',
        'bank_name',
        'account_name',
        'account_number',
        'routing_number',
        'account_type',
        'country',
        'currency',
        'verification_status',
        'withdrawal_status',
        'withdrawal_amount',
        'total_withdrawn_amount',
        'withdrawal_requested_at',
        'withdrawal_completed_at',
        'rejection_reason',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'withdrawal_requested_at' => 'datetime',
        'withdrawal_completed_at' => 'datetime',
        'withdrawal_amount' => 'decimal:2',
        'total_withdrawn_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function isPending(): bool
    {
        return $this->verification_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->verification_status === 'rejected';
    }

    public function verify(): void
    {
        $this->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);
    }

    public function reject(string $reason): void
    {
        $this->update([
            'verification_status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }
}

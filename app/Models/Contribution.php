<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contribution extends Model
{
    public const STATUSES = ['pending', 'confirmed', 'rejected'];

    public const TYPES = [
        'cooperative_buy_in',
        'ownership_expansion',
        'batch_participation',
        'settlement_reserve',
    ];

    protected $fillable = [
        'user_id',
        'batch_id',
        'amount',
        'currency',
        'status',
        'payment_reference',
        'contribution_type',
        'notes',
        'admin_notes',
        'approved_by_admin_id',
        'approved_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function approvingAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_admin_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function confirm(?User $admin = null, ?string $adminNotes = null): void
    {
        $this->update([
            'status' => 'confirmed',
            'approved_by_admin_id' => $admin?->id,
            'approved_at' => now(),
            'admin_notes' => $adminNotes,
        ]);
    }

    public function reject(?User $admin = null, ?string $adminNotes = null): void
    {
        $this->update([
            'status' => 'rejected',
            'approved_by_admin_id' => $admin?->id,
            'approved_at' => now(),
            'admin_notes' => $adminNotes,
        ]);
    }

    public function getTypeLabel(): string
    {
        return match ($this->contribution_type) {
            'cooperative_buy_in' => 'Cooperative Buy-In',
            'ownership_expansion' => 'Ownership Expansion',
            'batch_participation' => 'Batch Participation',
            'settlement_reserve' => 'Settlement Reserve',
            default => 'Unknown',
        };
    }

    public function getReferenceCodeAttribute(): string
    {
        return $this->payment_reference;
    }
}

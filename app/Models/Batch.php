<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Batch extends Model
{
    use HasFactory;

    public const STATUSES = ['pending', 'upcoming', 'active', 'archived', 'locked', 'completed'];

    protected $fillable = [
        'title',
        'slug',
        'description',
        'batch_code',
        'start_date',
        'end_date',
        'status',
        'max_members',
        'current_members',
        'ownership_level',
        'participation_fee',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'participation_fee' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Batch $batch): void {
            $batch->slug = $batch->slug ?: Str::slug($batch->title).'-'.Str::lower(Str::random(5));
            $batch->batch_code = $batch->batch_code ?: 'CCA-BATCH-'.Str::upper(Str::random(6));
        });
    }

    public function accessTokens(): HasMany
    {
        return $this->hasMany(AccessToken::class);
    }

    public function batchMembers(): HasMany
    {
        return $this->hasMany(BatchMember::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'batch_members')
            ->withPivot(['access_token_id', 'participation_status', 'joined_at'])
            ->withTimestamps();
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(Contribution::class);
    }

    public function settlements(): HasMany
    {
        return $this->hasMany(Settlement::class);
    }

    public function isOpenForParticipation(): bool
    {
        $today = Carbon::today();

        if (! $this->is_active || ! in_array($this->status, ['active', 'pending'], true)) {
            return false;
        }

        if ($this->start_date && $today->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $today->gt($this->end_date)) {
            return false;
        }

        return $this->max_members === 0 || $this->current_members < $this->max_members;
    }

    public function progressPercentage(): int
    {
        if ($this->max_members < 1) {
            return 0;
        }

        return min(100, (int) round(($this->current_members / $this->max_members) * 100));
    }
}

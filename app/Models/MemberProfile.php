<?php

namespace App\Models;

use Database\Factories\MemberProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberProfile extends Model
{
    /** @use HasFactory<MemberProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_legal_name',
        'phone',
        'date_of_birth',
        'gender',
        'country',
        'state',
        'city',
        'residential_address',
        'postal_code',
        'occupation',
        'ownership_interest_reason',
        'agricultural_interest_type',
        'bio',
        'onboarding_completed',
        'onboarding_completed_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'onboarding_completed' => 'boolean',
            'onboarding_completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function completionPercentage(): int
    {
        $required = [
            'full_legal_name',
            'phone',
            'country',
            'state',
            'city',
            'residential_address',
            'postal_code',
            'occupation',
            'ownership_interest_reason',
            'agricultural_interest_type',
            'bio',
        ];

        $completed = collect($required)
            ->filter(fn (string $field): bool => filled($this->{$field}))
            ->count();

        return (int) round(($completed / count($required)) * 100);
    }
}

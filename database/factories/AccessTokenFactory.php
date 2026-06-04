<?php

namespace Database\Factories;

use App\Models\AccessToken;
use App\Models\Batch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<AccessToken>
 */
class AccessTokenFactory extends Factory
{
    public function definition(): array
    {
        return [
            'token' => 'VIP'.Str::upper(Str::random(10)),
            'batch_id' => Batch::factory(),
            'ownership_tier' => 'standard',
            'price' => 250.00,
            'price_currency' => 'USD',
            'btc_wallet_address' => 'bc1qsecureportalvipwallet000000000000000',
            'assigned_to_user_id' => null,
            'status' => 'active',
            'expires_at' => now()->addWeek(),
            'used_at' => null,
            'revoked_at' => null,
            'created_by_admin_id' => User::factory()->admin(),
        ];
    }

    public function revoked(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'revoked',
            'revoked_at' => now(),
        ]);
    }

    public function used(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'used',
            'used_at' => now(),
        ]);
    }
}

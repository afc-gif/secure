<?php

namespace Database\Factories;

use App\Models\SettlementProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SettlementProfile>
 */
class SettlementProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'payout_platform' => 'cash_app',
            'cash_app_handle' => '$'.fake()->userName(),
            'bank_name' => 'Cash App',
            'account_name' => fake()->name(),
            'account_number' => '$'.fake()->userName(),
            'routing_number' => null,
            'country' => 'US',
            'currency' => 'USD',
            'verification_status' => fake()->randomElement(['pending', 'verified', 'rejected']),
            'rejection_reason' => null,
            'verified_at' => null,
        ];
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => 'verified',
            'verified_at' => now()->subDays(fake()->numberBetween(1, 90)),
            'rejection_reason' => null,
        ]);
    }
}

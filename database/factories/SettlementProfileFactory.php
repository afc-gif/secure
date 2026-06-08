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
            'payout_platform' => 'bank',
            'cash_app_handle' => null,
            'bank_name' => fake()->randomElement(['Chase Bank', 'Bank of America', 'Wells Fargo']),
            'account_name' => fake()->name(),
            'account_number' => fake()->numerify('##########'),
            'routing_number' => fake()->numerify('#########'),
            'account_type' => fake()->randomElement(['checking', 'savings']),
            'country' => 'US',
            'currency' => 'USD',
            'verification_status' => fake()->randomElement(['pending', 'verified', 'rejected']),
            'withdrawal_status' => null,
            'withdrawal_amount' => 0,
            'total_withdrawn_amount' => 0,
            'withdrawal_requested_at' => null,
            'withdrawal_completed_at' => null,
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

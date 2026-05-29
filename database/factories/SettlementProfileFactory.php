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
        $banks = ['Access Bank', 'GTBank', 'Zenith Bank', 'First Bank', 'UBA', 'Sterling Bank', 'Providus Bank'];

        return [
            'user_id' => User::factory(),
            'bank_name' => fake()->randomElement($banks),
            'account_name' => fake()->name(),
            'account_number' => fake()->numerify('##########'),
            'routing_number' => fake()->numerify('###'),
            'country' => 'NG',
            'currency' => 'NGN',
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

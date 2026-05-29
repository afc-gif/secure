<?php

namespace Database\Factories;

use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MemberProfile>
 */
class MemberProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'full_legal_name' => fake()->name(),
            'phone' => '2125550101',
            'date_of_birth' => fake()->dateTimeBetween('-60 years', '-21 years')->format('Y-m-d'),
            'gender' => 'prefer_not_to_say',
            'country' => 'United States',
            'state' => 'NY',
            'city' => 'New York',
            'residential_address' => '120 Maple Ridge Avenue',
            'postal_code' => '10001',
            'occupation' => 'Product Operator',
            'ownership_interest_reason' => 'I want structured exposure to agricultural ownership cycles through a trusted cooperative.',
            'agricultural_interest_type' => 'crop_cycles',
            'bio' => 'I am interested in sustainable rural production and long-term cooperative growth.',
            'onboarding_completed' => false,
            'onboarding_completed_at' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'onboarding_completed' => true,
            'onboarding_completed_at' => now(),
        ]);
    }
}

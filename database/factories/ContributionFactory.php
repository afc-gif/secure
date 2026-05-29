<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Contribution;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Contribution>
 */
class ContributionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'batch_id' => Batch::factory(),
            'amount' => fake()->randomFloat(2, 50000, 2500000),
            'currency' => 'NGN',
            'status' => fake()->randomElement(Contribution::STATUSES),
            'payment_reference' => 'CCA-DEMO-'.Str::upper(Str::random(10)),
            'contribution_type' => fake()->randomElement(Contribution::TYPES),
            'notes' => fake()->optional()->sentence(10),
            'admin_notes' => null,
            'approved_by_admin_id' => null,
            'approved_at' => null,
        ];
    }

    public function confirmed(?User $admin = null): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
            'approved_by_admin_id' => $admin?->id,
            'approved_at' => now()->subDays(fake()->numberBetween(1, 90)),
            'admin_notes' => 'Confirmed for cooperative ownership ledger.',
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'approved_by_admin_id' => null,
            'approved_at' => null,
            'admin_notes' => null,
        ]);
    }

    public function rejected(?User $admin = null): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'approved_by_admin_id' => $admin?->id,
            'approved_at' => now()->subDays(fake()->numberBetween(1, 45)),
            'admin_notes' => 'Rejected pending clearer payment evidence.',
        ]);
    }
}

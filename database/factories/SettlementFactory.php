<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Settlement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Settlement>
 */
class SettlementFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'processing', 'completed', 'failed', 'cancelled']);

        return [
            'user_id' => User::factory(),
            'batch_id' => Batch::factory(),
            'amount' => fake()->randomFloat(2, 250, 6500),
            'status' => $status,
            'processed_by_admin_id' => null,
            'processed_at' => $status === 'completed' ? now()->subDays(fake()->numberBetween(1, 60)) : null,
            'reference_number' => 'CCA-SETTLE-'.Str::upper(Str::random(10)),
            'notes' => fake()->optional()->sentence(8),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Batch;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Batch>
 */
class BatchFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->words(2, true);

        return [
            'title' => Str::title($title),
            'slug' => Str::slug($title).'-'.Str::lower(Str::random(5)),
            'description' => fake()->sentence(12),
            'batch_code' => 'CCA-BATCH-'.Str::upper(Str::random(6)),
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'status' => 'active',
            'max_members' => 50,
            'current_members' => 0,
            'ownership_level' => 'standard',
            'participation_fee' => null,
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'locked',
            'is_active' => false,
        ]);
    }
}

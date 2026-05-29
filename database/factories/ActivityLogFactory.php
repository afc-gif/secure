<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    public function definition(): array
    {
        $action = fake()->randomElement([
            'onboarding_completed',
            'token_activated',
            'contribution_submitted',
            'contribution_approved',
            'settlement_updated',
            'batch_joined',
        ]);

        return [
            'user_id' => User::factory(),
            'action' => $action,
            'description' => str($action)->replace('_', ' ')->title().' in demo cooperative ledger.',
            'metadata' => ['source' => 'development_seed'],
            'ip_address' => fake()->ipv4(),
        ];
    }
}

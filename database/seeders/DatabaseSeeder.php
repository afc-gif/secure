<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->admin()->create([
            'name' => 'CCA Admin',
            'email' => 'admin@countrycultureacres.com',
            'reference_token' => 'CCA-ADMIN0001',
        ]);

        User::factory()->create([
            'name' => 'CCA Member',
            'email' => 'member@countrycultureacres.com',
            'reference_token' => 'CCA-MEMBER001',
        ]);
    }
}

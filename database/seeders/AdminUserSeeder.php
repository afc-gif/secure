<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@secureportal.test'],
            [
                'name' => 'Super Admin',
                'phone' => '08000000000',
                'password' => Hash::make('Admin@12345'),
                'role' => 'admin',
                'reference_token' => 'CCA-ADMIN-DEMO',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
    }
}

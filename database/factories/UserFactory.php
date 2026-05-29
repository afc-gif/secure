<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = [
            'Adebayo Okafor',
            'Chioma Nwosu',
            'Tunde Balogun',
            'Amina Bello',
            'Kelechi Eze',
            'Musa Ibrahim',
            'Yetunde Adeyemi',
            'Ifeanyi Obi',
            'Zainab Sani',
            'Folake Williams',
        ];

        return [
            'name' => fake()->randomElement($names),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '080'.fake()->numerify('########'),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'member',
            'reference_token' => 'CCA-'.Str::upper(Str::random(10)),
            'status' => 'active',
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

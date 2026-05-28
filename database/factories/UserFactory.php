<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
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
        return [
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'room_number' => fake()->optional()->numerify('Room ###'),
            'mobile_number' => fake()->phoneNumber(),
            'country' => fake()->country(),
            'address' => fake()->address(),
            'religion' => 'Muslim',
            'gender' => 'Male',
            'date_of_birth' => fake()->date(),
            'course_type' => 'BSC',
            'department' => 'Automobile',
            'course_year' => '1st Year',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'status' => 'approved',
            'remember_token' => Str::random(10),
        ];
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

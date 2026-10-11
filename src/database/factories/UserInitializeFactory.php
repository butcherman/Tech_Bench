<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserInitialize;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<UserInitialize>
 */
class UserInitializeFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'username' => User::factory()->create()->username,
            'token' => Str::uuid(),
        ];
    }
}

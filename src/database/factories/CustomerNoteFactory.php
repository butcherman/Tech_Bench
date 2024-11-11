<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerNote>
 */
class CustomerNoteFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'cust_id' => Customer::factory(),
            'created_by' => User::inRandomOrder()->first()->user_id,
            'updated_by' => User::inRandomOrder()->first()->user_id,
            'urgent' => $this->faker->boolean(),
            'subject' => $this->faker->sentence(4),
            'details' => $this->faker->paragraph(),
        ];
    }
}

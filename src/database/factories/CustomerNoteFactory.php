<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerNoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model
     */
    protected $model = CustomerNote::class;

    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'cust_id' => Customer::factory(),
            'created_by' => User::inRandomOrder()->first()->user_id,
            'urgent' => $this->faker->boolean(),
            'subject' => $this->faker->sentence(4),
            'details' => $this->faker->paragraph(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerAlertFactory extends Factory
{
    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'cust_id' => Customer::factory(),
            'message' => $this->faker->word(),
            'type' => $this->faker->randomElement([
                'success',
                'warning',
                'danger',
            ]),
        ];
    }
}

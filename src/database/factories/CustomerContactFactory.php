<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerContact>
 */
class CustomerContactFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'cust_id' => Customer::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'title' => $this->faker->title(),
            'note' => $this->faker->word(),
            'local' => true,
            'decision_maker' => false,
        ];
    }
}

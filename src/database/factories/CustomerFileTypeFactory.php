<?php

namespace Database\Factories;

use App\Models\CustomerFileType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomerFileType>
 */
class CustomerFileTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence(4),
        ];
    }
}

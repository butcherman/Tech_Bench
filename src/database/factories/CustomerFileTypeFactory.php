<?php

namespace Database\Factories;

use App\Models\CustomerFileType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFileTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model
     */
    protected $model = CustomerFileType::class;

    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence(4),
        ];
    }
}

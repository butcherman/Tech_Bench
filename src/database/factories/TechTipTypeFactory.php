<?php

namespace Database\Factories;

use App\Models\TechTipType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TechTipType>
 */
class TechTipTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->realText(10),
        ];
    }
}

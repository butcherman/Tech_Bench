<?php

namespace Database\Factories;

use App\Models\EquipmentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model
     */
    protected $model = EquipmentCategory::class;

    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model
     */
    protected $model = EquipmentType::class;

    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'cat_id' => EquipmentCategory::factory(),
            'name' => $this->faker->unique()->word(1),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\EquipmentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EquipmentCategory>
 */
class EquipmentCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        do {
            $name = $this->faker->unique()->word();
        } while (EquipmentCategory::where('name', $name)->first());

        return [
            'name' => $name,
        ];
    }
}

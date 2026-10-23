<?php

namespace Database\Factories;

use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EquipmentType>
 */
class EquipmentTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        do {
            $name = $this->faker->unique()->word();
        } while (EquipmentType::where('name', $name)->first());

        return [
            'cat_id' => EquipmentCategory::factory(),
            'name' => $name,
            'allow_public_tip' => true,
        ];
    }
}

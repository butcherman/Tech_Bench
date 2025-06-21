<?php

namespace Database\Factories;

use App\Models\DataFieldType;
use App\Models\EquipmentType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataField>
 */
class DataFieldFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'equip_id' => EquipmentType::factory(),
            'type_id' => DataFieldType::factory(),
            'order' => 0,
        ];
    }
}

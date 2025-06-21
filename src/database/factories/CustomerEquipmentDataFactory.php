<?php

namespace Database\Factories;

use App\Models\CustomerEquipment;
use App\Models\DataField;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerEquipmentData>
 */
class CustomerEquipmentDataFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'cust_equip_id' => CustomerEquipment::factory(),
            'field_id' => DataField::factory(),
            'value' => $this->faker->word(),
        ];
    }
}

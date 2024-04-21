<?php

namespace Database\Factories;

use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use App\Models\DataField;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerEquipmentDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model
     */
    protected $model = CustomerEquipmentData::class;

    /**
     * Define the model's default state
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

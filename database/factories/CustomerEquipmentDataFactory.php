<?php

namespace Database\Factories;

use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerEquipmentDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerEquipmentData::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cust_equip_id' => CustomerEquipment::factory()->create()->cust_equip_id,
            'field_id' => 1,
            'value' => $this->faker->word(5),
        ];
    }
}

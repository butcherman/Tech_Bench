<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\EquipmentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerEquipmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerEquipment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cust_id' => Customer::factory()->create()->cust_id,
            'equip_id' => EquipmentType::factory()->create()->equip_id,
            'shared' => $this->faker->boolean(),
        ];
    }
}

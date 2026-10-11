<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\EquipmentType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomerEquipment>
 */
class CustomerEquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'cust_id' => Customer::factory(),
            'equip_id' => EquipmentType::factory(),
        ];
    }
}

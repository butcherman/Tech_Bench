<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\EquipmentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerEquipmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model
     */
    protected $model = CustomerEquipment::class;

    /**
     * Define the model's default state
     */
    public function definition()
    {
        return [
            'cust_id' => Customer::factory(),
            'equip_id' => EquipmentType::factory(),
        ];
    }
}

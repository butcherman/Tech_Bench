<?php

namespace Database\Factories;

use App\Models\CustomerEquipment;
use App\Models\CustomerSite;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerEquipmentSiteFactory extends Factory
{
    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'cust_site_id' => CustomerSite::factory()->create(),
            'cust_equip_id' => CustomerEquipment::factory()->create(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\CustomerContact;
use App\Models\CustomerSite;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerContactSiteFactory extends Factory
{
    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'cust_site_id' => CustomerSite::factory(),
            'cont_id' => CustomerContact::factory(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\CustomerNote;
use App\Models\CustomerSite;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerNoteSiteFactory extends Factory
{
    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'cust_site_id' => CustomerSite::factory(),
            'note_id' => CustomerNote::factory(),
        ];
    }
}

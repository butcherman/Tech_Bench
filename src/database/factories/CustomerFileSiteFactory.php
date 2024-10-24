<?php

namespace Database\Factories;

use App\Models\CustomerFile;
use App\Models\CustomerSite;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFileSiteFactory extends Factory
{
    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'cust_site_id' => CustomerSite::factory(),
            'cust_file_id' => CustomerFile::factory(),
        ];
    }
}

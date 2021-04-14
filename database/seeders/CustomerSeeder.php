<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     *  Create 50 random customers
     */
    public function run()
    {
        Customer::factory()->count(50)->create();
    }
}

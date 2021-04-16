<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\EquipmentType;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     *  Create 50 random customers
     */
    public function run()
    {
        Customer::factory()->count(50)->create();

        //  Assign equipment to 40 of those customers
        $custList = Customer::inRandomOrder()->limit(40)->get();
        foreach($custList as $cust)
        {
            CustomerEquipment::create([
                'cust_id'  => $cust->cust_id,
                'equip_id' => EquipmentType::inRandomOrder()->first()->equip_id,
                'shared'   => false,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use App\Models\CustomerNotes;
use App\Models\DataField;
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
            $newEquip = CustomerEquipment::create([
                'cust_id'  => $cust->cust_id,
                'equip_id' => EquipmentType::inRandomOrder()->first()->equip_id,
                'shared'   => false,
            ]);

            $equipFields = DataField::where('equip_id', $newEquip->equip_id)->orderBy('order')->get();
            foreach($equipFields as $field)
            {
                CustomerEquipmentData::factory()->create([
                    'cust_equip_id' => $newEquip->cust_equip_id,
                    'field_id'      => $field->field_id,
                ]);
            }
        }

        //  Assign notes to 20 of those customers
        $custList = Customer::inRandomOrder()->limit(20)->get();
        foreach($custList as $cust)
        {
            CustomerNote::factory()->create(['cust_id' => $cust->cust_id]);
        }

        //  Assign files to 10 of the customers
        $custList = Customer::inRandomOrder()->limit(10)->get();
        foreach($custList as $cust)
        {
            CustomerFile::factory()->create(['cust_id' => $cust->cust_id]);
        }
    }
}
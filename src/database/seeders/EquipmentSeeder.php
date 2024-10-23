<?php

namespace Database\Seeders;

use App\Models\DataField;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     *  Create sample categories and equipment (rather than relying on Faker to generate random words)
     */
    public function run(): void
    {
        // TODO - make a general seeder so it can be run more than once
        $equip = collect([
            'Cisco' => [
                '1900 Series',
                '1800 Series',
                '1000 Series',
                '2800 Series',
            ],
            'AdTran' => [
                'NetVanta 1500 Series',
                '900 Series',
                'NetVanta 1200 Series',
            ],
            'Hewlett-Packard' => [
                'HSR6800 Router Series',
                'MSR2000 Router Series',

            ],
        ]);

        //  Create the sample equipment
        foreach ($equip as $cat => $sys) {
            $catID = EquipmentCategory::create(['name' => $cat])->cat_id;
            foreach ($sys as $s) {
                EquipmentType::create([
                    'cat_id' => $catID,
                    'name' => $s,
                ]);
            }
        }

        // Create the information to gather when adding a system mto a customer
        $equipList = EquipmentType::all();
        foreach ($equipList as $e) {
            for ($i = 1; $i < 7; $i++) {

                DataField::create([
                    'equip_id' => $e->equip_id,
                    'type_id' => $i,
                    'order' => $i - 1,
                ]);
            }
        }
    }
}

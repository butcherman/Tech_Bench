<?php

use App\SystemTypes;
use App\SystemCategories;
use App\SystemDataFields;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  Create sample categories and equipment
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
        //  Create the samle equipment
        foreach($equip as $cat => $sys)
        {
            $catID = SystemCategories::create(['name' => $cat])->cat_id;
            foreach($sys as $s)
            {
                SystemTypes::create([
                    'cat_id' => $catID,
                    'name' => $s,
                    'folder_location' => str_replace(' ', '_', $s),
                ]);
            }
        }

        // Create the information to gather when adding a syste mto a customer
        $sysList = SystemTypes::all();
        foreach($sysList as $sys)
        {
            for($i = 1; $i < 7; $i++)
            {

                SystemDataFields::create([
                    'sys_id' => $sys->sys_id,
                    'data_type_id' => $i,
                    'order' => $i - 1,
                ]);
            }
        }
    }
}

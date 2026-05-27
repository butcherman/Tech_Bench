<?php

namespace Database\Seeders;

use App\Models\DataField;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     *  Create sample categories and equipment
     * (rather than relying on Faker to generate random words)
     */
    public function run(): void
    {
        $equip = collect([
            'Phones' => [
                'NEC SV9100',
                'NEC SV9300',
                'Mitel MiVoice Connect',
                'Mitel MiVoice Business',
            ],
            'CCTV' => [
                'Avivilon Unity',
                'Pelco VXPro',
                'Ava',
            ],
            'Clocks & Bells' => [
                'Valcom Class Connection',
                'Bogen',
            ],
        ]);

        //  Create the sample equipment
        foreach ($equip as $cat => $sys) {
            if (!EquipmentCategory::where('name', $cat)->count()) {
                $catID = EquipmentCategory::create(['name' => $cat])->cat_id;
            }

            foreach ($sys as $s) {
                if (!EquipmentType::where('name', $s)->count()) {
                    EquipmentType::create([
                        'cat_id' => $catID,
                        'name' => $s,
                    ]);
                }
            }
        }

        // Update the Phones equipment to allow Public Tips
        EquipmentType::where('cat_id', 1)->update(['allow_public_tip' => true]);

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

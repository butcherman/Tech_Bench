<?php

namespace Database\Seeders;

use App\Models\TechTipType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TechTipTypeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Default Tech Tip Types
     */
    public function run(): void
    {
        $defaultData = [
            [
                'tip_type_id' => 1,
                'description' => 'Tech Tip',
            ],
            [
                'tip_type_id' => 2,
                'description' => 'Documentation',
            ],
            [
                'tip_type_id' => 3,
                'description' => 'Software',
            ],
        ];

        // Only insert the data if no other data has been added
        if (TechTipType::doesntExist()) {
            TechTipType::insertOrIgnore($defaultData);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'tip_type_id' => 2,
                'description' => 'Documentation',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'tip_type_id' => 3,
                'description' => 'Software',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
        ];

        DB::table('tech_tip_types')->insertOrIgnore($defaultData);
    }
}

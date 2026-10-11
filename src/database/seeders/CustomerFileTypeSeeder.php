<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerFileTypeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Default Customer File Types
     */
    public function run(): void
    {
        $defaultData = [
            [
                'file_type_id' => 1,
                'description' => 'Equipment Backup',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'file_type_id' => 2,
                'description' => 'License',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'file_type_id' => 3,
                'description' => 'Site Map',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'file_type_id' => 4,
                'description' => 'Other',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
        ];

        DB::table('customer_file_types')->insertOrIgnore($defaultData);
    }
}

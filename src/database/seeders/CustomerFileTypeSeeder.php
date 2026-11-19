<?php

namespace Database\Seeders;

use App\Models\CustomerFileType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            ],
            [
                'file_type_id' => 2,
                'description' => 'License',
            ],
            [
                'file_type_id' => 3,
                'description' => 'Site Map',
            ],
            [
                'file_type_id' => 4,
                'description' => 'Other',            ],
        ];

        if (CustomerFileType::doesntExist()) {
            CustomerFileType::insert($defaultData);
        }
    }
}

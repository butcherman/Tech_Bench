<?php

namespace Database\Seeders;

use App\Models\DataFieldType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataFieldTypesSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Default Data Types for customer equipment
     */
    public function run(): void
    {
        $defaultData = [
            [
                'type_id' => 1,
                'name' => 'IP Address',
                'pattern' => null,
                'pattern_error' => null,
                'is_hyperlink' => false,
                'allow_copy' => false,
                'do_not_log_value' => false,
                'masked' => false,
            ],
            [
                'type_id' => 2,
                'name' => 'Version',
                'pattern' => null,
                'pattern_error' => null,
                'is_hyperlink' => false,
                'allow_copy' => false,
                'do_not_log_value' => false,
                'masked' => false,
            ],
            [
                'type_id' => 3,
                'name' => 'Login Username',
                'pattern' => null,
                'pattern_error' => null,
                'is_hyperlink' => false,
                'allow_copy' => false,
                'do_not_log_value' => false,
                'masked' => false,
            ],
            [
                'type_id' => 4,
                'name' => 'Login Password',
                'pattern' => null,
                'pattern_error' => null,
                'is_hyperlink' => false,
                'allow_copy' => false,
                'do_not_log_value' => false,
                'masked' => true,
            ],
            [
                'type_id' => 5,
                'name' => 'Remote Access',
                'pattern' => null,
                'pattern_error' => null,
                'is_hyperlink' => false,
                'allow_copy' => false,
                'do_not_log_value' => false,
                'masked' => false,
            ],
            [
                'type_id' => 6,
                'name' => 'Subnet Mask',
                'pattern' => null,
                'pattern_error' => null,
                'is_hyperlink' => false,
                'allow_copy' => false,
                'do_not_log_value' => false,
                'masked' => false,
            ],
            [
                'type_id' => 7,
                'name' => 'Default Gateway',
                'pattern' => null,
                'pattern_error' => null,
                'is_hyperlink' => false,
                'allow_copy' => false,
                'do_not_log_value' => false,
                'masked' => false,
            ],
            [
                'type_id' => 8,
                'name' => 'Primary DNS',
                'pattern' => null,
                'pattern_error' => null,
                'is_hyperlink' => false,
                'allow_copy' => false,
                'do_not_log_value' => false,
                'masked' => false,
            ],
            [
                'type_id' => 9,
                'name' => 'Secondary DNS',
                'pattern' => null,
                'pattern_error' => null,
                'is_hyperlink' => false,
                'allow_copy' => false,
                'do_not_log_value' => false,
                'masked' => false,
            ],
        ];

        // Only input the default data if the table is empty so we don't re-add
        // any data that was intentionally removed.
        if (DataFieldType::doesntExist()) {
            DataFieldType::insertOrIgnore($defaultData);
        }
    }
}

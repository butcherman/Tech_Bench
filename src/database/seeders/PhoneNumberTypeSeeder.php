<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhoneNumberTypeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Default Phone Number Types.
     */
    public function run(): void
    {
        $defaultData = [
            [
                'phone_type_id' => 1,
                'description' => 'Home',
                'icon_class' => 'fa-home',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'phone_type_id' => 2,
                'description' => 'Work',
                'icon_class' => 'fa-briefcase',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'phone_type_id' => 3,
                'description' => 'Mobile',
                'icon_class' => 'fa-mobile-alt',
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
        ];

        DB::table('phone_number_types')->insertOrIgnore($defaultData);
    }
}

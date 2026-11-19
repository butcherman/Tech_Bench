<?php

namespace Database\Seeders;

use App\Models\PhoneNumberType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            ],
            [
                'phone_type_id' => 2,
                'description' => 'Work',
                'icon_class' => 'fa-briefcase',
            ],
            [
                'phone_type_id' => 3,
                'description' => 'Mobile',
                'icon_class' => 'fa-mobile-alt',
            ],
        ];

        PhoneNumberType::insertOrIgnore($defaultData);
    }
}

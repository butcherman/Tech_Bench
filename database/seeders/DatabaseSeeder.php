<?php

namespace Database\Seeders;

use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //  Make it so that the admin password is not expired
        User::find(1)->update([
            'password_expires' => null,
        ]);
        //  Create 10 random users
        User::factory(10)->create();

        //  Create 5 random equipment categories and 2 equipment types for each
        EquipmentCategory::factory()->count(5)->has(EquipmentType::factory()->count(2))->create();
    }
}

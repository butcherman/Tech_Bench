<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Create the default Admin User.
     */
    public function run(): void
    {
        $defaultUser = [
            'user_id' => 1,
            'role_id' => 1,
            'username' => 'admin',
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'email' => 'admin@em.com',
            'password' => bcrypt('password'),
            'password_expires' => '2000-01-01 00:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('users')->insertOrIgnore($defaultUser);

        $settings = [
            [
                'setting_id' => 1,
                'user_id' => 1,
                'setting_type_id' => 1,
                'value' => 1,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'setting_id' => 2,
                'user_id' => 1,
                'setting_type_id' => 2,
                'value' => 1,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
            [
                'setting_id' => 3,
                'user_id' => 1,
                'setting_type_id' => 3,
                'value' => 1,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ],
        ];

        DB::table('user_settings')->insertOrIgnore($settings);
    }
}

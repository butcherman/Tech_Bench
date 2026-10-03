<?php

namespace Database\Seeders;

use App\Facades\CacheData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AppSettingsSeeder::class,
            UserRoleSeeder::class,
            UserSettingsSeeder::class,
            UserSeeder::class,
            DataFieldTypesSeeder::class,
            PhoneNumberTypeSeeder::class,
            CustomerFileTypeSeeder::class,
            TechTipTypeSeeder::class,
        ]);

        CacheData::clearCache();
    }
}

<?php

namespace Database\Seeders;

use App\Facades\CacheData;
use App\Models\AppSettings;
use App\Models\User;
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
        $this->initializeApp();

        $this->call([
            UserSeeder::class,
            EquipmentSeeder::class,
            CustomerSeeder::class,
            TechTipSeeder::class,
            FileLinkSeeder::class,
        ]);

        CacheData::clearCache();
    }

    /**
     * Initialize the App
     */
    protected function initializeApp(): void
    {
        // Turn off first time setup
        $settingId = AppSettings::find(1);
        if ($settingId) {
            $settingId->delete();
        }

        // Set Admin User's password to not be expired
        User::find(1)->update([
            'password_expires' => null,
        ]);

        // Set Pacific Timezone
        AppSettings::create([
            'key' => 'timezone',
            'value' => 'America/Los_Angeles',
        ]);
    }
}

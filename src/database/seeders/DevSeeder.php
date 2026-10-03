<?php

namespace Database\Seeders;

use App\Facades\CacheData;
use App\Models\AppSettings;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DevSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            DatabaseSeeder::class,
            DevUserSeeder::class,
            DevEquipmentSeeder::class,
            DevCustomerSeeder::class,
            DevTechTipSeeder::class,
            DevFileLinkSeeder::class,
        ]);

        $this->initializeApp();

        CacheData::clearCache();
    }

    /**
     * Initialize the App
     */
    protected function initializeApp(): void
    {
        // Turn off first time setup
        $firstSetup = AppSettings::where('key', 'app.first_time_setup')->first();

        if ($firstSetup) {
            $firstSetup->delete();
        }

        // Set Admin User's password to not be expired
        User::find(1)->update([
            'password_expires' => null,
        ]);

        // Set Pacific Timezone
        AppSettings::create([
            'key' => 'timezone',
            'value' => json_encode('America/Los_Angeles'),
        ]);
    }
}

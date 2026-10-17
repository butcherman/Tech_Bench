<?php

namespace Database\Seeders;

use App\Models\AppSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppSettingsSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Create default App Settings data
     */
    public function run(): void
    {
        /**
         * Allow First time setup to run.
         */
        $firstTimeInit = [[
            'id' => 1,
            'key' => 'app.first_time_setup',
            'value' => json_encode(true),
            'created_at' => NOW(),
            'updated_at' => NOW(),
        ]];

        $hasEntries = AppSettings::all()->count();

        if ($hasEntries === 0) {
            DB::table('app_settings')->insert($firstTimeInit);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\UserSettingType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSettingsSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Add Default User Settings
     */
    public function run(): void
    {
        $default = [
            [
                'setting_type_id' => 1,
                'name' => 'Receive Email Notifications',
                'description' => 'Receive email notifications from '.config('app.name'),
                'perm_type_id' => null,
                'feature_name' => null,
                'config_key' => null,
            ],
            [
                'setting_type_id' => 2,
                'name' => 'Auto Delete Expired File Links',
                'description' => 'Auto delete file links and attached files after they have been expired for a set amount of time',
                'perm_type_id' => null,
                'feature_name' => 'file-link.feature_enabled',
                'config_key' => 'file-link.auto_delete_override',
            ],
            [
                'setting_type_id' => 3,
                'name' => 'Receive System Backup Notifications',
                'description' => 'Receive an email when a backup is successful or failed',
                'perm_type_id' => 1,
                'feature_name' => null,
                'config_key' => null,
            ],
        ];

        UserSettingType::upsert($default, ['setting_type_id', 'name']);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserSetting;
use App\Models\UserSettingType;
use App\Traits\AppSettingsTrait;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use AppSettingsTrait;

    /**
     *  Create 10 random users with different roles
     */
    public function run()
    {
        //  Make it so that the admin password is not expired
        User::find(1)->update([
            'password_expires' => null,
        ]);

        //  Turn off the "first time setup" flag
        $this->clearSetting('app.first_time_setup');

        //  Create a Tech User that has restricted access
        User::create([
            'user_id' => 2,
            'role_id' => 4,
            'username' => 'tech',
            'first_name' => 'Tech',
            'last_name' => 'User',
            'email' => 'tech@em.com',
            'password' => bcrypt('password'),
            'password_expires' => null,
        ]);

        //  Create 10 users each with a different role_id
        $newUsers = User::factory()->count(10)->state(new Sequence(
            ['role_id' => 2],
            ['role_id' => 3],
            ['role_id' => 4]
        ))->create();

        //  Create the user settings for each of the new users
        $settingData = UserSettingType::all();

        foreach ($settingData as $setting) {
            UserSetting::create([
                'user_id' => 2,
                'setting_type_id' => $setting->setting_type_id,
                'value' => true,
            ]);
        }

        foreach ($newUsers as $user) {
            foreach ($settingData as $setting) {
                UserSetting::create([
                    'user_id' => $user->user_id,
                    'setting_type_id' => $setting->setting_type_id,
                    'value' => false,
                ]);
            }
        }

        //  Create 10 users and disable them
        $disabledUsers = User::factory()->count(5)->create();
        foreach ($disabledUsers as $user) {
            $user->delete();
        }
    }
}

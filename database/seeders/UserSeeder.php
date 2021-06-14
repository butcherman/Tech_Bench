<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserSetting;
use App\Models\UserSettingType;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     *  Create 10 random users with different roles
     */
    public function run()
    {
         //  Make it so that the admin password is not expired
         User::find(1)->update([
            'password_expires' => null,
        ]);

        //  Create 10 users each with a different role_id
        $newUsers = User::factory()->count(10)->state(new Sequence(
            ['role_id' => 1],
            ['role_id' => 2],
            ['role_id' => 3],
            ['role_id' => 4]
        ))->create();

        //  Create the user settings for each of the new users
        $settingData = UserSettingType::all();
        foreach($newUsers as $user)
        {
            foreach($settingData as $setting)
            {
                UserSetting::create([
                    'user_id'         => $user->user_id,
                    'setting_type_id' => $setting->setting_type_id,
                    'value'           => true,
                ]);
            }
        }
    }
}

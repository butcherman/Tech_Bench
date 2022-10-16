<?php

namespace App\Listeners\Admin;

use App\Events\Admin\UserCreatedEvent;
use App\Models\UserSetting;
use App\Models\UserSettingType;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateUserSettingsEntry implements ShouldQueue
{
    /**
     * Create the Settings Data Entries for the new user
     */
    public function handle(UserCreatedEvent $event)
    {
        $settings = UserSettingType::all();
        foreach($settings as $setting)
        {
            UserSetting::create([
                'user_id'         => $event->user->user_id,
                'setting_type_id' => $setting->setting_type_id,
                'value'           => true,
            ]);
        }
    }
}

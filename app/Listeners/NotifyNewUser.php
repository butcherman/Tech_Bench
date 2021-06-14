<?php

namespace App\Listeners;

use Illuminate\Support\Str;
use App\Events\NewUserCreated;
use App\Models\UserInitialize;
use App\Models\UserSetting;
use App\Models\UserSettingType;
use App\Notifications\SendWelcomeEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifyNewUser
{
    /**
     * Create the event listener.
     */
    // public function __construct()
    // {
    //     //
    // }

    /**
     * Handle the event.
     */
    public function handle(NewUserCreated $event)
    {
        //  Add all setting data to the user
        $settingData = UserSettingType::all();
        foreach($settingData as $setting)
        {
            UserSetting::create([
                'user_id'         => $event->user->user_id,
                'setting_type_id' => $setting->setting_type_id,
                'value'           => true,
            ]);
        }

        //  Create the new users Initialization Link
        UserInitialize::create([
            'username' => $event->user->username,
            'token'    => $token = Str::uuid(),
        ]);

        Notification::send($event->user, new SendWelcomeEmail($event->user, $token));
    }
}

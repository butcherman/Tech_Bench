<?php

namespace App\Listeners\User;

use App\Events\User\UserCreatedEvent;
use App\Models\UserSetting;
use App\Models\UserSettingType;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class CreateUserSettingsEntry implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(UserCreatedEvent $event): void
    {
        Log::debug('Building User Settings for New User '.$event->user->full_name);
        $settings = UserSettingType::all();
        foreach ($settings as $setting) {
            UserSetting::create([
                'user_id' => $event->user->user_id,
                'setting_type_id' => $setting->setting_type_id,
                'value' => true,
            ]);
        }
    }
}

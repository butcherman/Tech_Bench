<?php

namespace App\Jobs\User;

use App\Models\User;
use App\Models\UserSetting;
use App\Models\UserSettingType;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CreateUserSettingsEntriesJob implements ShouldQueue
{
    use Queueable;

    public function __construct(protected User $user) {}

    /**
     * Create a database table entry of user settings for the new user.
     * By default, all values are set to true
     */
    public function handle(): void
    {
        Log::debug('Building User Settings for New User '.$this->user->full_name);

        $settings = UserSettingType::all();

        foreach ($settings as $setting) {
            UserSetting::create([
                'user_id' => $this->user->user_id,
                'setting_type_id' => $setting->setting_type_id,
                'value' => true,
            ]);
        }
    }
}

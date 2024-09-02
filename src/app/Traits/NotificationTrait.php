<?php

namespace App\Traits;
use App\Models\User;
use App\Models\UserSettingType;

trait NotificationTrait
{
    protected function getViaArray(User $user)
    {
        $settingId = UserSettingType::where('name', 'Receive Email Notifications')
            ->first()
            ->setting_type_id;

        $allowEmail = $user->UserSetting
            ->where('setting_type_id', $settingId)
            ->first()
            ->value;

        return $allowEmail ? ['mail', 'broadcast'] : ['broadcast'];
    }
}
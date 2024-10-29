<?php

namespace App\Services\User;

use App\Events\User\UserSettingsUpdatedEvent;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Support\Collection;

class UserSettingsService
{
    /**
     * Update Basic Account settings (name, email)
     */
    public function updateUserAccount(Collection $requestData, User $user): void
    {
        $user->update($requestData->toArray());
    }

    public function updateUserSettings(Collection $requestData, User $user): void
    {
        foreach ($requestData->get('settingList') as $key => $value) {
            UserSetting::where('user_id', $user->user_id)
                ->where('setting_type_id', str_replace('type_id_', '', $key))
                ->update(['value' => $value]);
        }

        event(new UserSettingsUpdatedEvent($user));
    }
}

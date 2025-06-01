<?php

namespace App\Services\User;

use App\Events\User\UserSettingsUpdatedEvent;
use App\Models\User;
use App\Models\UserSetting;
use App\Models\UserSettingType;
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

    /**
     * Update the users Settings Data (primarily notification settings).
     */
    public function updateUserSettings(Collection $requestData, User $user): void
    {
        foreach ($requestData->get('settingList') as $key => $value) {
            UserSetting::where('user_id', $user->user_id)
                ->where('setting_type_id', str_replace('type_id_', '', $key))
                ->update(['value' => $value]);
        }
    }

    /**
     * Verify that all users have all available settings options
     */
    public function verifyUserSettings(bool $fix): array
    {
        $failed = [];
        $settingType = UserSettingType::all()->pluck('setting_type_id');
        $userList = User::withTrashed()->with('UserSettings')->get();

        // Go through each user and verify they have all setting type
        foreach ($userList as $user) {
            $userSettings = $user->UserSettings->pluck('setting_type_id');
            $missing = $settingType->diff($userSettings);

            if ($missing->isNotEmpty()) {
                $failed[] = [
                    'user_id' => $user->user_id,
                    'full_name' => $user->full_name,
                    'setting_type_id' => $missing->flatten(),
                ];

                // Fix if the fix flag is turned on
                if ($fix) {
                    foreach ($missing as $setting) {
                        $settingData = new UserSetting([
                            'setting_type_id' => $setting,
                            'value' => true,
                        ]);

                        $user->UserSettings()->save($settingData);
                    }
                }
            }
        }

        return $failed;
    }
}

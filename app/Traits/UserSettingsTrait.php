<?php

namespace App\Traits;

use App\Models\User;
use App\Models\UserSetting;
use App\Models\UserRolePermissions;

trait UserSettingsTrait
{
    protected function filterUserSettings(User $user)
    {
        //  Pull the user settings
        $userSettings = UserSetting::where('user_id', $user->user_id)->with('UserSettingType')->get();

        // dd($userSettings);

        //  TODO -> Finish this - Filter out any settings that the user is not allowed to adjust
        // foreach($userSettings as $key => $setting)
        // {
        //     $allowed = UserRolePermissions::where('role_id', $user->user_id)->where('perm_type_id', $setting->UserSettingType->perm_type_id)->first(); // ->allow;

        //     dd($allowed);
        //     if(!$allowed)
        //     {
        //         $userSettings->forget($key);
        //     }
        // }

        return $userSettings;
    }
}

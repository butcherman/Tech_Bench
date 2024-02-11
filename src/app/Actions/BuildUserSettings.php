<?php

namespace App\Actions;

use App\Models\User;
use App\Models\UserRolePermission;
use App\Models\UserSetting;

/**
 * Build a list of user settings that the user can make adjustments to.
 * Skip any that the user does not have permission to adjust
 */
class BuildUserSettings
{
    public static function build(User $user)
    {
        $userSettings = UserSetting::where('user_id', $user->user_id)->get();

        foreach ($userSettings as $key => $setting) {
            //  Determine if this setting is linked to a permission feature
            // (i.e. should not be displayed if the user cannot access the feature)
            // Note, not used yet
            // @codeCoverageIgnoreStart
            if (! is_null($setting->UserSettingType->perm_type_id)) {
                $allowed = UserRolePermission::where('role_id', $user->role_id)
                    ->where('perm_type_id', $setting->UserSettingType->perm_type_id)->first();

                if (! $allowed->allow) {
                    $userSettings->forget($key);
                }
            }
            // @codeCoverageIgnoreEnd
        }

        return $userSettings;
    }
}

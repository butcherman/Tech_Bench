<?php

namespace App\Actions;

use App\Models\User;
use App\Models\UserRolePermission;
use App\Models\UserSetting;
use Illuminate\Database\Eloquent\Collection;

/**
 * Build a list of user settings that the user can make adjustments to.
 * Skip any that the user does not have permission to adjust
 */
class BuildUserSettings
{
    public static function build(User $user): Collection
    {
        $userSettings = UserSetting::where('user_id', $user->user_id)->get();

        foreach ($userSettings as $key => $setting) {
            /**
             * Determine if this setting is linked to a feature or permission feature
             * (i.e. should not be displayed if the user cannot access the feature)
             */
            if (
                ! is_null($setting->UserSettingType->perm_type_id) ||
                ! is_null($setting->UserSettingType->feature_name) ||
                ! is_null($setting->UserSettingType->config_key)
            ) {
                // Check a configuration setting
                $config = (bool) config($setting->UserSettingType->config_key) ?? true;
                // Check a Feature setting
                if ($setting->UserSettingType->feature_name) {
                    $enabled = $user->features()->active($setting->UserSettingType->feature_name) ?? true;
                } else {
                    $enabled = true;
                }
                // Check a Role Permission
                $allowed = UserRolePermission::where('role_id', $user->role_id)
                    ->where('perm_type_id', $setting->UserSettingType->perm_type_id)->first()->allow ?? true;

                if (! $allowed || ! $enabled || ! $config) {
                    $userSettings->forget($key);
                }
            }
        }

        return $userSettings;
    }
}

<?php

namespace App\Actions\User;

use App\Models\User;
use App\Models\UserRolePermission;
use App\Models\UserSettingType;
use Illuminate\Database\Eloquent\Collection;

class BuildUserSettings
{
    /** @var User */
    protected $user;

    /**
     * Build a list of user settings that the user can make adjustments to.
     * Skip any that the user does not have permission to adjust
     *
     * @return Collection<UserRolePermission>
     */
    public function __invoke(User $user): Collection
    {
        $this->user = $user;
        $userSettings = $user->UserSettings;

        // Verify we do not need to remove any settings user does not have permission for
        foreach ($userSettings as $key => $setting) {
            if ($this->shouldSettingBeHidden($setting->UserSettingType)) {
                $userSettings->forget($key);
            }
        }

        return $userSettings;
    }

    /**
     * Determine if this setting is linked to a feature or permission feature
     * (i.e. should not be displayed if the user cannot access the feature)
     */
    protected function shouldSettingBeHidden(UserSettingType $type): bool
    {
        // If perm_type_id is filled, determine if allowed
        if (! is_null($type->perm_type_id)) {
            if (! $this->isPermissionTypeAllowed($type->perm_type_id)) {
                return true;
            }
        }

        // If config_key is filled, determine if Configuration enabled
        if (! is_null($type->config_key)) {
            if (! $this->isConfigEnabled($type->config_key)) {
                return true;
            }
        }

        // if feature_name is filled, determine if Feature is enabled for user
        if (! is_null($type->feature_name)) {
            if (! $this->isFeatureEnabled($type->feature_name)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a User Role Permission is enabled for user
     */
    protected function isPermissionTypeAllowed(int $permTypeId): bool
    {
        return (bool) UserRolePermission::where('role_id', $this->user->role_id)
            ->where('perm_type_id', $permTypeId)->first()->allow;
    }

    /**
     * Determine if a Configuration Flag is enabled
     */
    protected function isConfigEnabled(string $configKey): bool
    {
        return (bool) config($configKey);
    }

    /**
     * Determine if a user has permission to use a feature
     */
    protected function isFeatureEnabled(string $featureName): bool
    {
        return $this->user->features()->active($featureName);
    }
}

<?php

namespace App\Service\Maint;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserRolePermissionType;
use App\Models\UserSetting;
use App\Models\UserSettingType;

class UserMaintenanceService
{
    public function __construct(protected bool $fix = false)
    {
        //
    }

    public function handle()
    {
        $reportData = [
            'full_level_admin_users' => $this->getInstallerUsers(),
        ];

        return $reportData;
    }

    /**
     * Return a list of users with Installer Access
     */
    public function getInstallerUsers()
    {
        return User::whereRoleId(1)
            ->get(['user_id', 'first_name', 'last_name'])
            ->makeHidden(['initials', 'role_name', 'full_name'])
            ->makeVisible('user_id');
    }

    /**
     * Return a list of user with any type of Admin Access
     */
    public function getAdminUsers()
    {
        $roleList = UserRole::whereHas('UserRolePermission', function ($q) {
            $q->where('allow', true)->whereHas('UserRolePermissionType', function ($q2) {
                $q2->where('is_admin_link', true);
            });
        })->get()->pluck('role_id');

        return User::whereIn('role_id', $roleList)
            ->get(['user_id', 'first_name', 'last_name'])
            ->makeHidden(['initials', 'role_name', 'full_name'])
            ->makeVisible('user_id');
    }

    /**
     * Return an array with a count of active and non-active users
     */
    public function getUserCount($type)
    {
        return match ($type) {
            'active' => User::all()->count(),
            'disabled' => User::onlyTrashed()->get()->count(),
        };
    }

    /**
     * Verify that all users have all available settings options
     */
    public function verifyUserSettings()
    {
        $failed = [];
        $settingType = UserSettingType::all()->pluck('setting_type_id');
        $userList = User::withTrashed()->with('UserSetting')->get();

        // Go through each user and verify they have all setting type
        foreach ($userList as $user) {
            $userSettings = $user->UserSetting->pluck('setting_type_id');
            $missing = $settingType->diff($userSettings);

            if ($missing->isNotEmpty()) {
                $failed[] = [
                    'user_id' => $user->user_id,
                    'full_name' => $user->full_name,
                    'setting_type_id' => $missing->flatten(),
                ];
            }
        }

        return $failed;
    }

    /**
     * Fix any user profiles that are missing settings
     */
    public function fixUserSettings($failedUsers)
    {
        foreach ($failedUsers as $user) {
            foreach ($user['setting_type_id'] as $id) {
                UserSetting::create([
                    'user_id' => $user['user_id'],
                    'setting_type_id' => $id,
                    'value' => true,
                ]);
            }
        }
    }
}
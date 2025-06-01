<?php

namespace App\Notifications\Maintenance\Backup;

use App\Models\User;
use App\Traits\AllowTrait;
use Spatie\Backup\Notifications\Notifiable;

class BackupNotifiable extends Notifiable
{
    use AllowTrait;

    /**
     * user_role_permission_type needed to get Email Notifications.
     *
     * @var string
     */
    protected $permissionNeeded = 'App Settings';

    /**
     * user_setting_type needed to get Email Notifications.
     *
     * @var string
     */
    protected $userSettingField = 'Receive System Backup Notifications';

    /**
     * Users with Permission to App Settings and Notifications turned on should
     * get the backup notification emails.
     */
    public function routeNotificationForMail(): string|array
    {
        $userList = $this->getUsersWithPermission($this->permissionNeeded);

        $filtered = $userList->filter(function ($user) {
            return $user->checkUserSetting($this->userSettingField);
        });

        return $filtered->pluck('email')->toArray();
    }
}

<?php

namespace App\Policies;

use App\User;
use App\UserPermissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class GatePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //  Determine if the user is a Installer/Super Admin
    public function isInstaller(User $user)
    {
        if($user->is_installer)
        {
            return true;
        }

        return false;
    }

    //  Determine if a user can see the Administration Nav Link
    public function seeAdminLink(User $user)
    {
        $permissions = UserPermissions::find($user->user_id);

        if($this->isInstaller($user))
        {
            return true;
        }
        else if($permissions->manage_users || $permissions->create_category || $permissions->modify_category)
        {
            return true;
        }

        return false;
    }

    //  Determine if a user has permissions for a task
    public function hasAccess(User $user, $task)
    {
        //  Check if user is super user first
        if($this->isInstaller($user))
        {
            return true;
        }

        $permissions = UserPermissions::select($task)->where('user_id', $user->user_id)->first();

        return $permissions->$task;
    }
}

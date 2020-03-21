<?php

namespace App\Policies;

use App\User;
use App\UserRolePermissions;
use Illuminate\Support\Facades\Log;
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
        $role = User::find($user->user_id);

        if($role->role_id == 1)
        {
            return true;
        }

        return false;
    }

    //  Determine if a user can see the Administration Nav Link
    public function seeAdminLink(User $user)
    {
        if ($this->isInstaller($user))
        {
            return true;
        }

        $data = UserRolePermissions::with('UserRolePermissionTypes')
            ->whereHas('UserRolePermissionTypes', function ($query) {
                $query->where('description', 'Manage Users')
                    ->orWhere('description', 'Manage User Roles')
                    ->orWhere('description', 'Manage Customers')
                    ->orWhere('description', 'Manage Equipment');
            })
            ->where('role_id', $user->role_id)
            ->where('allow', 1)
            ->get();

        $allow = $data->isEmpty() ? 'false' : 'true';
        Log::debug('User ' . $user->full_name . ' is trying to access admin link.  Result - ' . $allow);

        return  $data->isEmpty() ? false : true;
    }

    //  Determine if a user has permissions for a task
    public function hasAccess(User $user, $task)
    {
        //  Check if user is super user first
        if($this->isInstaller($user))
        {
            return true;
        }

        $data = UserRolePermissions::with('UserRolePermissionTypes')
            ->whereHas('UserRolePermissionTypes', function ($query) use ($task) {
                $query->where('description', $task);
            })
            ->where('role_id', $user->role_id)
            ->where('allow', 1)
            ->get();

        $allow = $data->isEmpty() ? 'false' : 'true';
        Log::debug('User '.$user->full_name.' is trying to access '.$task.'.  Result - ' . $allow);

        return  $data->isEmpty() ? false : true;
    }
}

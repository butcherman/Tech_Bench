<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserRolePermissions;
use App\Models\UserRoles;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class UserRolePermissionsPolicy
{
    use HandlesAuthorization;

    /*
    *   Allow anyone with "Manage Permissions" permission
    */
    public function before(User $user)
    {
        $allowed = UserRolePermissions::whereRoleId($user->role_id)->whereHas('UserRolePermissionTypes', function($q)
        {
            $q->where('description', 'Manage Permissions');
        })->first();

        Log::channel('auth')->debug('User '.$user->username.' is checking User Role Permissions Policy access. Result - '.($allowed->allow ? 'Allow' : 'Deny'));

        if($allowed)
        {
            return $allowed->allow;
        }
    }

    /**
     *  Only administrators can do this
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     *  Only administrators can do this
     */
    public function view(User $user, UserRolePermissions $userRolePermissions)
    {
        return false;
    }

    /**
     *  Only administrators can do this
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     *  Only administrators can do this
     */
    public function update(User $user, UserRolePermissions $userRolePermissions)
    {
        return false;
    }

    /**
     *  Only administrators can do this
     */
    public function delete(User $user, UserRolePermissions $userRolePermissions)
    {
        return false;
    }

    /**
     *  Only administrators can do this
     */
    public function restore(User $user, UserRolePermissions $userRolePermissions)
    {
        return false;
    }

    /**
     *  Only administrators can do this
     */
    public function forceDelete(User $user, UserRolePermissions $userRolePermissions)
    {
        return false;
    }
}

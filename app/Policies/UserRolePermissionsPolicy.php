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

        if($allowed)
        {
            return $allowed;
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
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRolePermissions  $userRolePermissions
     * @return mixed
     */
    public function view(User $user, UserRolePermissions $userRolePermissions)
    {
        //
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
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRolePermissions  $userRolePermissions
     * @return mixed
     */
    public function delete(User $user, UserRolePermissions $userRolePermissions)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRolePermissions  $userRolePermissions
     * @return mixed
     */
    public function restore(User $user, UserRolePermissions $userRolePermissions)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRolePermissions  $userRolePermissions
     * @return mixed
     */
    public function forceDelete(User $user, UserRolePermissions $userRolePermissions)
    {
        //
    }
}

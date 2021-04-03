<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserRolePermissions;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    use HandlesAuthorization;

    /*
    *   Allow anyone with "Manage Users" permission
    */
    public function before(User $user, $method)
    {
        $allowed = UserRolePermissions::whereRoleId($user->role_id)->whereHas('UserRolePermissionTypes', function($q)
        {
            $q->where('description', 'Manage Users');
        })->first();

        Log::channel('auth')->debug('User '.$user->username.' is checking Admin access to '.$method.'.  Result - '.($allowed->allow ? 'Allow' : 'Deny'));

        return $allowed->allow;
    }

    /**
     *  Users can only update their own profiles
     */
    public function view(User $user, User $model)
    {
        return $user->user_id === $model->user_id;
    }

    /**
     *  Only users with "Manage Users" access can create a new user
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     *  A user can update their own profile
     */
    public function update(User $user, User $model)
    {
        // dd($model);
        return $user->user_id === $model->user_id;
    }

    /**
     *  Only users with Manage Users access can disable a user
     */
    public function delete(User $user, User $model)
    {
        return false;
    }

    /**
     *  Only admin's can restore a user
     */
    public function restore(User $user, User $model)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserEmailNotifications;
use App\Models\UserRolePermissions;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class UserEmailNotificationsPolicy
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

        Log::channel('auth')->debug('User '.$user->username.' is checking User Email Notification Policy access to '.$method.'.  Result - '.($allowed->allow ? 'Allow' : 'Deny'));

        if($allowed->allow)
        {
            return $allowed->allow;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\odel=UserEmailNotifications  $odel=UserEmailNotifications
     * @return mixed
     */
    public function view(User $user, UserEmailNotifications $UserEmailNotifications)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\odel=UserEmailNotifications  $odel=UserEmailNotifications
     * @return mixed
     */
    public function update(User $user, UserEmailNotifications $UserEmailNotifications)
    {
        return $user->user_id === $UserEmailNotifications->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\odel=UserEmailNotifications  $odel=UserEmailNotifications
     * @return mixed
     */
    public function delete(User $user, UserEmailNotifications $UserEmailNotifications)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\odel=UserEmailNotifications  $odel=UserEmailNotifications
     * @return mixed
     */
    public function restore(User $user, UserEmailNotifications $UserEmailNotifications)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\odel=UserEmailNotifications  $odel=UserEmailNotifications
     * @return mixed
     */
    public function forceDelete(User $user, UserEmailNotifications $UserEmailNotifications)
    {
        //
    }
}

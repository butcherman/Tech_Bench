<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserRoles;
use App\Traits\AllowTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserRolesPolicy
{
    use HandlesAuthorization;
    use AllowTrait;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
        return $this->checkPermission($user, 'Manage Permissions');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRoles  $userRoles
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, UserRoles $userRoles)
    {
        //
        return $this->checkPermission($user, 'Manage Permissions');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
        return $this->checkPermission($user, 'Manage Permissions');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRoles  $userRoles
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, UserRoles $userRoles)
    {
        //
        return $this->checkPermission($user, 'Manage Permissions');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRoles  $userRoles
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, UserRoles $userRoles)
    {
        //
        return $this->checkPermission($user, 'Manage Permissions');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRoles  $userRoles
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, UserRoles $userRoles)
    {
        //
        return $this->checkPermission($user, 'Manage Permissions');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserRoles  $userRoles
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, UserRoles $userRoles)
    {
        //
        return $this->checkPermission($user, 'Manage Permissions');
    }
}
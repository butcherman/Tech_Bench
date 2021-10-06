<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\AllowTrait;

use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;
    use AllowTrait;

    /**
     * Determine whether the user can create models
     */
    public function create(User $user)
    {
        return $this->checkPermission($user, 'Manage Users');
    }

    /**
     * Determine whether the user can update the user profile
     */
    public function update(User $user, User $model)
    {
        if($this->checkPermission($user, 'Manage Users'))
        {
            //  If they user has permission to Manage Users, they cannot manage anyone with a higher role than themselves
            if($user->role_id > $model->role_id)
            {
                return Response::deny('You cannot modify a user with higher permissions than yourself');
            }

            return true;
        }

        return $user->user_id === $model->user_id;
    }

    /**
     * Determine whether the user can delete the model
     */
    public function delete(User $user, User $model)
    {
        $this->checkPermission($user, 'Manage Users');
    }

    /**
     * Determine whether the user can restore the model
     */
    public function restore(User $user, User $model)
    {
        $this->checkPermission($user, 'Manage Users');
    }
}

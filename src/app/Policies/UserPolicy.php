<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\AllowTrait;

class UserPolicy
{
    use AllowTrait;

    /**
     * Determine if the user can view a list of all models.
     */
    public function manage(User $user): bool
    {
        return $this->checkPermission($user, 'Manage Users');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        if ($user->user_id === $model->user_id) {
            return true;
        }

        return $this->checkPermission($user, 'Manage Users')
            && $user->role_id <= $model->role_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->checkPermission($user, 'Manage Users');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if ($user->user_id !== $model->user_id) {
            return $this->checkPermission($user, 'Manage Users')
                && $user->role_id <= $model->role_id;
        }

        return $user->user_id === $model->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $this->checkPermission($user, 'Manage Users');
    }
}

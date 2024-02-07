<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\AllowTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use AllowTrait;
    use HandlesAuthorization;

    public function view(User $user, User $model)
    {
        if ($user->user_id !== $model->user_id) {
            return $this->checkPermission($user, 'Manage Users')
                && $user->role_id <= $model->role_id;
        }

        return $user->user_id === $model->user_id;
    }

    public function create(User $user)
    {
        return $this->checkPermission($user, 'Manage Users');
    }

    public function update(User $user, User $model)
    {
        if ($user->user_id !== $model->user_id) {
            return $this->checkPermission($user, 'Manage Users')
                && $user->role_id <= $model->role_id;
        }

        return $user->user_id === $model->user_id;
    }

    public function destroy(User $user, User $model)
    {
        return $this->checkPermission($user, 'Manage Users');
    }
}

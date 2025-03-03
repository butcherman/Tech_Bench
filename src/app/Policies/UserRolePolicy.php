<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserRole;
use App\Traits\AllowTrait;

class UserRolePolicy
{
    use AllowTrait;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->checkPermission($user, 'Manage Permissions');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $this->checkPermission($user, 'Manage Permissions');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->checkPermission($user, 'Manage Permissions');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserRole $userRole): bool
    {
        if (! $userRole->allow_edit) {
            return false;
        }

        return $this->checkPermission($user, 'Manage Permissions');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserRole $userRole): bool
    {
        if (! $userRole->allow_edit) {
            return false;
        }

        return $this->checkPermission($user, 'Manage Permissions');
    }
}

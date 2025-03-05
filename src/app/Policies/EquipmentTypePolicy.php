<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\AllowTrait;

class EquipmentTypePolicy
{
    use AllowTrait;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->checkPermission($user, 'Manage Equipment');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->checkPermission($user, 'Manage Equipment');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $this->checkPermission($user, 'Manage Equipment');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $this->checkPermission($user, 'Manage Equipment');
    }
}

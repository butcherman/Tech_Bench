<?php

namespace App\Policies;

use App\Models\EquipmentCategory;
use App\Models\User;
use App\Traits\AllowTrait;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EquipmentCategoryPolicy
{
    use AllowTrait;

    /**
     * Determine whether the user can create models
     */
    public function create(User $user)
    {
        return $this->checkPermission($user, 'Manage Equipment');
    }

    /**
     * Determine whether the user can update the model
     */
    public function update(User $user)
    {
        return $this->checkPermission($user, 'Manage Equipment');
    }

    /**
     * Determine whether the user can delete the model
     */
    public function delete(User $user)
    {
        return $this->checkPermission($user, 'Manage Equipment');
    }
}

<?php

namespace App\Policies;

use App\Models\EquipmentWorkbook;
use App\Models\User;
use App\Traits\AllowTrait;

class EquipmentWorkbookPolicy
{
    use AllowTrait;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EquipmentWorkbook $equipmentWorkbook): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->checkPermission($user, 'Manage Equipment Workbooks');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EquipmentWorkbook $equipmentWorkbook): bool
    {
        return $this->checkPermission($user, 'Manage Equipment Workbooks');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EquipmentWorkbook $equipmentWorkbook): bool
    {
        return $this->checkPermission($user, 'Manage Equipment Workbooks');
    }
}

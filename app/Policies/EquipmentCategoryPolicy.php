<?php

namespace App\Policies;

use App\Models\EquipmentCategory;
use App\Models\User;
use App\Traits\AllowTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class EquipmentCategoryPolicy
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
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EquipmentCategory  $equipmentCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, EquipmentCategory $equipmentCategory)
    {
        //
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
        return $this->checkPermission($user, 'Manage Equipment');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EquipmentCategory  $equipmentCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, EquipmentCategory $equipmentCategory)
    {
        //
        return $this->checkPermission($user, 'Manage Equipment');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EquipmentCategory  $equipmentCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, EquipmentCategory $equipmentCategory)
    {
        //
        return $this->checkPermission($user, 'Manage Equipment');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EquipmentCategory  $equipmentCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, EquipmentCategory $equipmentCategory)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EquipmentCategory  $equipmentCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, EquipmentCategory $equipmentCategory)
    {
        //
    }
}

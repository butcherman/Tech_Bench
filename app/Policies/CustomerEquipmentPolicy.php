<?php

namespace App\Policies;

use App\Traits\AllowTrait;

use App\Models\User;
use App\Models\CustomerEquipment;

use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerEquipmentPolicy
{
    use HandlesAuthorization;
    use AllowTrait;

    /**
     *  Determine if the user can create new equipment for the customer
     */
    public function create(User $user)
    {
        return $this->checkPermission($user, 'Add Customer Equipment');
    }

    /**
     *  Determine if the user can update the information for the given equipment
     */
    public function update(User $user, CustomerEquipment $customerEquipment)
    {
        return $this->checkPermission($user, 'Edit Customer Equipment');
    }

    /**
     *  Determine if the user can delete Customer Equipment
     */
    public function delete(User $user, CustomerEquipment $customerEquipment)
    {
        return $this->checkPermission($user, 'Delete Customer Equipment');
    }

    /**
     *  Determine if the user can restore deleted customer equipment
     */
    public function restore(User $user, CustomerEquipment $customerEquipment)
    {
        return $this->checkPermission($user, 'Delete Customer Equipment');
    }

    /**
     *  Determine if the user can completely delete customer equipment
     */
    public function forceDelete(User $user, CustomerEquipment $customerEquipment)
    {
        return $this->checkPermission($user, 'Delete Customer');
    }
}
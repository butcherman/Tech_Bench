<?php

namespace App\Policies;

use App\Models\CustomerContact;
use App\Models\User;
use App\Traits\AllowTrait;
use Illuminate\Auth\Access\Response;

class CustomerContactPolicy
{
    use AllowTrait;

    /**
     *  Determine if the user can create a contact
     */
    public function create(User $user)
    {
        return $this->checkPermission($user, 'Add Customer Contact');
    }

    /**
     *  Determine if the user can update a contact
     */
    public function update(User $user)
    {
        return $this->checkPermission($user, 'Edit Customer Contact');
    }

    /**
     *  Determine if the user can delete a contact
     */
    public function delete(User $user)
    {
        return $this->checkPermission($user, 'Delete Customer Contact');
    }

    /**
     *  Determine if the user can restore a deleted contact
     */
    public function restore(User $user)
    {
        return $this->checkPermission($user, 'Manage Customers');
    }

    /**
     *  Determine if the user can permanently delete a contact
     */
    public function forceDelete(User $user)
    {
        return $this->checkPermission($user, 'Manage Customers');
    }
}

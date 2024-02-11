<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\AllowTrait;

class CustomerSitePolicy
{
    use AllowTrait;

    /*
    * Manage customers determines if they can deactivate and recover customers
    */
    public function manage(User $user)
    {
        return $this->checkPermission($user, 'Manage Customers');
    }

    /**
     * Determine whether the user can create new customers
     */
    public function create(User $user)
    {
        return $this->checkPermission($user, 'Add Customer');
    }

    /**
     * Determine whether the user can update the customer
     */
    public function update(User $user)
    {
        return $this->checkPermission($user, 'Update Customer');
    }

    /**
     * Determine whether the user can delete the customer
     */
    public function delete(User $user)
    {
        return $this->checkPermission($user, 'Deactivate Customer');
    }

    /**
     * Determine whether the user can restore the customer
     */
    public function restore(User $user)
    {
        return $this->checkPermission($user, 'Deactivate Customer');
    }

    /**
     * Determine whether the user can permanently delete the customer
     */
    public function forceDelete(User $user)
    {
        return $this->checkPermission($user, 'Delete Customer');
    }
}

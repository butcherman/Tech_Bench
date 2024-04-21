<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\AllowTrait;

class CustomerSitePolicy
{
    use AllowTrait;

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
}

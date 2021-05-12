<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;
use App\Traits\AllowTrait;

use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;
    use AllowTrait;

    /*
    *   Manage customers determines if they can deactivate and recover customers
    */
    public function manage(User $user)
    {
        return $this->checkPermission($user, 'Manage Customers');
    }

    /**
     *  Determine whether the user can create new customers
     */
    public function create(User $user)
    {
        return $this->checkPermission($user, 'Add Customer');
    }

    /**
     *  Determine if the user can update the customers basic details
     */
    public function update(User $user, Customer $customer)
    {
        return $this->checkPermission($user, 'Update Customer');
    }

    /**
     *  Determine if the user can deactivate the customer
     */
    public function delete(User $user, Customer $customer)
    {
        return $this->checkPermission($user, 'Deactivate Customer');
    }

    /**
     *  Determine if the user can re-activate a deactivated customer
     */
    public function restore(User $user, Customer $customer)
    {
        return $this->checkPermission($user, 'Manage Customers');
    }

    /**
     *  Determine if the user can permanently delete the customer and all associated information
     */
    public function forceDelete(User $user, Customer $customer)
    {
        return $this->checkPermission($user, 'Delete Customers');
    }
}

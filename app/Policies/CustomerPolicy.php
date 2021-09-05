<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;
use App\Traits\AllowTrait;

use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use AllowTrait;
    use HandlesAuthorization;

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
    public function update(User $user, Customer $customer)
    {
        return $this->checkPermission($user, 'Update Customer');
    }

    /**
     * Determine whether the user can delete the customer
     */
    public function delete(User $user, Customer $customer)
    {
        return $this->checkPermission($user, 'Deactivate Customer');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Customer $customer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Customer $customer)
    {
        //
    }
}

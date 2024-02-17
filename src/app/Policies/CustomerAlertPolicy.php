<?php

namespace App\Policies;

use App\Models\CustomerAlert;
use App\Models\User;
use App\Traits\AllowTrait;
use Illuminate\Auth\Access\Response;

class CustomerAlertPolicy
{
    use AllowTrait;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->checkPermission($user, 'Manage Customers');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->checkPermission($user, 'Manage Customers');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CustomerAlert $customerAlert): bool
    {
        return $this->checkPermission($user, 'Manage Customers');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CustomerAlert $customerAlert): bool
    {
        return $this->checkPermission($user, 'Manage Customers');
    }
}

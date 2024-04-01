<?php

namespace App\Policies;

use App\Models\CustomerFile;
use App\Models\User;
use App\Traits\AllowTrait;
use Illuminate\Auth\Access\Response;

class CustomerFilePolicy
{
    use AllowTrait;

    /**
     *  Determine if the user can add a new customer file
     */
    public function create(User $user)
    {
        return $this->checkPermission($user, 'Add Customer File');
    }

    /**
     *  Determine if the user can update the file properties
     */
    public function update(User $user)
    {
        return $this->checkPermission($user, 'Edit Customer File');
    }

    /**
     *  Determine if the user can delete a customer file
     */
    public function delete(User $user)
    {
        return $this->checkPermission($user, 'Delete Customer File');
    }

    /**
     *  Determine if a user can restore a deleted file
     */
    public function restore(User $user)
    {
        return $this->checkPermission($user, 'Manage Customers');
    }

    /**
     *  Determine if a user can permanently delete a file
     */
    public function forceDelete(User $user)
    {
        return $this->checkPermission($user, 'Manage Customers');
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\AllowTrait;

class CustomerNotePolicy
{
    use AllowTrait;

    /**
     *  Determine if the user can create a new note
     */
    public function create(User $user)
    {
        return $this->checkPermission($user, 'Add Customer Note');
    }

    /**
     *  Determine if the user can update an existing note
     */
    public function update(User $user)
    {
        return $this->checkPermission($user, 'Edit Customer Note');
    }

    /**
     *  Determine if the user can soft delete a note
     */
    public function delete(User $user)
    {
        return $this->checkPermission($user, 'Delete Customer Note');
    }

    /**
     *  Determine if the user can restore a deleted note
     */
    public function restore(User $user)
    {
        return $this->checkPermission($user, 'Manage Customers');
    }

    /**
     *  Determine if the user can permanently delete a note
     */
    public function forceDelete(User $user)
    {
        return $this->checkPermission($user, 'Manage Customers');
    }
}

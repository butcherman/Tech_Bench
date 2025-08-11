<?php

namespace App\Policies;

use App\Models\CustomerWorkbook;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CustomerWorkbookPolicy
{
    /**
     * Determine if the user gets to view internal only values
     */
    public function viewProtectedValues(User $user): bool
    {
        return $user->role_id ? true : false;
    }

    /**
     * Determine whether the user can view any models.
     */
    // public function viewAny(User $user): bool
    // {
    //     return false;
    // }

    /**
     * Determine whether the user can view the model.
     */
    // public function view(User $user, CustomerWorkbook $customerWorkbook): bool
    // {
    //     return false;
    // }

    /**
     * Determine whether the user can create models.
     */
    // public function create(User $user): bool
    // {
    //     return false;
    // }

    /**
     * Determine whether the user can update the model.
     */
    // public function update(User $user, CustomerWorkbook $customerWorkbook): bool
    // {
    //     return false;
    // }

    /**
     * Determine whether the user can delete the model.
     */
    // public function delete(User $user, CustomerWorkbook $customerWorkbook): bool
    // {
    //     return false;
    // }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, CustomerWorkbook $customerWorkbook): bool
    // {
    //     return false;
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, CustomerWorkbook $customerWorkbook): bool
    // {
    //     return false;
    // }
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TechTip;
use App\Traits\AllowTrait;

use Illuminate\Auth\Access\HandlesAuthorization;

class TechTipPolicy
{
    use AllowTrait;
    use HandlesAuthorization;

    /*
    *   Determine if a user can Manage Tech Tips
    */
    public function manage(User $user)
    {
        return $this->checkPermission($user, 'Manage Tech Tips');
    }

    /**
     *  Determine if a user can add a new Tech Tip
     */
    public function create(User $user)
    {
        return $this->checkPermission($user, 'Add Tech Tip');
    }

    /**
     *  Determine if a user can edit an existing Tech Tip
     */
    public function update(User $user)
    {
        return $this->checkPermission($user, 'Edit Tech Tip');
    }

    /**
     *  Determine if a user can soft delete a Tech Tip
     */
    public function delete(User $user)
    {
        return $this->checkPermission($user, 'Delete Tech Tip');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TechTip  $techTip
     * @return mixed
     */
    public function restore(User $user, TechTip $techTip)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TechTip  $techTip
     * @return mixed
     */
    public function forceDelete(User $user, TechTip $techTip)
    {
        //
    }
}

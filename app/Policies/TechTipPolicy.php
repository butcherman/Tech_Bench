<?php

namespace App\Policies;

use App\Models\TechTip;
use App\Models\User;
use App\Traits\AllowTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class TechTipPolicy
{
    use HandlesAuthorization;
    use AllowTrait;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TechTip  $techTip
     * @return mixed
     */
    public function view(User $user, TechTip $techTip)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return $this->checkPermission($user, 'Add Tech Tip');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TechTip  $techTip
     * @return mixed
     */
    public function update(User $user, TechTip $techTip)
    {
        //
        return $this->checkPermission($user, 'Edit Tech Tip');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TechTip  $techTip
     * @return mixed
     */
    public function delete(User $user, TechTip $techTip)
    {
        //
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

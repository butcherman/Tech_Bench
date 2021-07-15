<?php

namespace App\Policies;

use App\Models\TechTipComment;
use App\Models\User;
use App\Traits\AllowTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class TechTipCommentPolicy
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
     * @param  \App\Models\TechTipComment  $techTipComment
     * @return mixed
     */
    public function view(User $user, TechTipComment $techTipComment)
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
        return $this->checkPermission($user, 'Comment on Tech Tip');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TechTipComment  $techTipComment
     * @return mixed
     */
    public function update(User $user, TechTipComment $techTipComment)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TechTipComment  $techTipComment
     * @return mixed
     */
    public function delete(User $user, TechTipComment $techTipComment)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TechTipComment  $techTipComment
     * @return mixed
     */
    public function restore(User $user, TechTipComment $techTipComment)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TechTipComment  $techTipComment
     * @return mixed
     */
    public function forceDelete(User $user, TechTipComment $techTipComment)
    {
        //
    }
}

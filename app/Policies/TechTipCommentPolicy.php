<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TechTipComment;

use App\Traits\AllowTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class TechTipCommentPolicy
{
    use AllowTrait;
    use HandlesAuthorization;

    public function manage(User $user)
    {
        return $this->checkPermission($user, 'Manage Tech Tips');
    }

    /**
     * Determine whether the user can create models
     */
    public function create(User $user)
    {
        return $this->checkPermission($user, 'Comment on Tech Tip');
    }

    /**
     * Determine whether the user can update the model
     */
    public function update(User $user, TechTipComment $techTipComment)
    {
        return $user->user_id == $techTipComment->user_id || $this->checkPermission($user, 'Manage Tech Tips');
    }

    /**
     * Determine whether the user can delete the model
     */
    public function delete(User $user, TechTipComment $techTipComment)
    {
        return $user->user_id == $techTipComment->user_id || $this->checkPermission($user, 'Manage Tech Tips');
    }
}

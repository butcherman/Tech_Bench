<?php

namespace App\Policies;

use App\Models\TechTipComment;
use App\Models\User;
use App\Traits\AllowTrait;

class TechTipCommentPolicy
{
    use AllowTrait;

    protected $permName = 'Comment on Tech Tip';

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->checkPermission($user, $this->permName);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TechTipComment $techTipComment): bool
    {
        return $techTipComment->user_id === $user->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TechTipComment $techTipComment): bool
    {
        return $this->checkPermission($user, 'Manage Tech Tips')
            || $techTipComment->user_id === $user->user_id;
    }
}

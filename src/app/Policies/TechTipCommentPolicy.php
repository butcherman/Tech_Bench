<?php

namespace App\Policies;

use App\Features\TechTipCommentFeature;
use App\Models\TechTipComment;
use App\Models\User;
use App\Traits\AllowTrait;

class TechTipCommentPolicy
{
    use AllowTrait;

    /**
     * Determine who has management abilities for comments
     */
    public function manage(User $user): bool
    {
        if ($user->features()->inactive(TechTipCommentFeature::class)) {
            return false;
        }

        return $this->checkPermission($user, 'Manage Tech Tips');
    }

    /**
     * Determine whether the user can create comments.
     */
    public function create(User $user): bool
    {
        if ($user->features()->inactive(TechTipCommentFeature::class)) {
            return false;
        }

        return $this->checkPermission($user, 'Comment on Tech Tip');
    }

    /**
     * Determine whether the user can update the comment.
     */
    public function update(User $user, TechTipComment $techTipComment): bool
    {
        if ($user->features()->inactive(TechTipCommentFeature::class)) {
            return false;
        }

        return $techTipComment->user_id === $user->user_id;
    }

    /**
     * Determine whether the user can delete the comment.
     */
    public function delete(User $user, TechTipComment $techTipComment): bool
    {
        if ($user->features()->inactive(TechTipCommentFeature::class)) {
            return false;
        }

        return $this->checkPermission($user, 'Manage Tech Tips')
            || $techTipComment->user_id === $user->user_id;
    }
}

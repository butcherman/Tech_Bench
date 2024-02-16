<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserPinnedLink;
use App\Traits\AllowTrait;
use Illuminate\Auth\Access\Response;

class UserPinnedLinkPolicy
{
    use AllowTrait;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $this->checkPermission($user, 'Manage Users');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserPinnedLink $userPinnedLink): bool
    {
        if ($user->user_id === $userPinnedLink->user_id) {
            return true;
        }

        return $this->checkPermission($user, 'Manage Users');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserPinnedLink $userPinnedLink): bool
    {
        if ($user->user_id === $userPinnedLink->user_id) {
            return true;
        }

        return $this->checkPermission($user, 'Manage Users');
    }
}

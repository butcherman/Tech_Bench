<?php

namespace App\Policies;

use App\Features\FileLinkFeature;
use App\Models\FileLink;
use App\Models\User;
use App\Traits\AllowTrait;

class FileLinkPolicy
{
    use AllowTrait;

    public function manage(User $user)
    {
        if ($this->viewAny($user)) {
            return $this->checkPermission($user, 'Manage File Links');
        }

        return false;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->features()->active(FileLinkFeature::class);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FileLink $fileLink): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FileLink $fileLink): bool
    {
        if ($this->viewAny($user)) {
            return $user->user_id === $fileLink->user_id ||
                $this->checkPermission($user, 'Manage File Links');
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FileLink $fileLink): bool
    {
        if ($this->viewAny($user)) {
            return $user->user_id === $fileLink->user_id ||
                $this->checkPermission($user, 'Manage File Links');
        }

        return false;
    }
}

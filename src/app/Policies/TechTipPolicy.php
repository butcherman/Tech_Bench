<?php

namespace App\Policies;

use App\Features\PublicTechTipFeature;
use App\Models\User;
use App\Traits\AllowTrait;

class TechTipPolicy
{
    use AllowTrait;

    public function manage(User $user)
    {
        return $this->checkPermission($user, 'Manage Tech Tips');
    }

    /**
     * Determine whether the user can create models
     */
    public function create(User $user)
    {
        return $this->checkPermission($user, 'Add Tech Tip');
    }

    /**
     * Determine whether the user can update the model
     */
    public function update(User $user)
    {
        return $this->checkPermission($user, 'Edit Tech Tip');
    }

    /**
     * Determine whether the user can delete the model
     */
    public function delete(User $user)
    {
        return $this->checkPermission($user, 'Delete Tech Tip');
    }

    /**
     * Determine if a public Tech Tip can be created
     */
    public function public(User $user)
    {
        if ($user->features()->inactive(PublicTechTipFeature::class)) {
            return false;
        }

        return $this->checkPermission($user, 'Add Public Tech Tip');
    }
}

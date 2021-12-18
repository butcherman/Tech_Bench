<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\AllowTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppSettingsPolicy
{
    use AllowTrait;
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models
     */
    public function viewAny(User $user)
    {
        return $this->checkPermission($user, 'App Settings');
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\AllowTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppSettingsPolicy
{
    use AllowTrait;
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $this->checkPermission($user, 'App Settings');
    }

    public function update(User $user)
    {
        return $this->checkPermission($user, 'App Settings');
    }
}

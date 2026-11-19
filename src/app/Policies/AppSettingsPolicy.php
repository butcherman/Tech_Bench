<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\AllowTrait;

class AppSettingsPolicy
{
    use AllowTrait;

    public function update(User $user): bool
    {
        return $this->checkPermission($user, 'App Settings');
    }
}

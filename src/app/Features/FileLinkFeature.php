<?php

namespace App\Features;

use App\Models\User;
use App\Models\UserRolePermission;
use App\Traits\AllowTrait;
use Illuminate\Support\Lottery;

class FileLinkFeature
{
    use AllowTrait;

    /**
     * Resolve the feature's initial value.
     */
    public function resolve(User $user): mixed
    {
        if (!config('fileLink.feature_enabled')) {
            return false;
        }

        return $this->checkPermission($user, 'Use File Links');
    }
}
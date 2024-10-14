<?php

namespace App\Features;

use App\Models\User;
use App\Traits\AllowTrait;

class FileLinkFeature
{
    use AllowTrait;

    /**
     * Determine if File Link Feature is enabled.
     * Then determine if user has permission to use File Link Feature
     */
    public function resolve(User $user): bool
    {
        if (! config('file-link.feature_enabled')) {
            return false;
        }

        return $this->checkPermission($user, 'Use File Links');
    }
}

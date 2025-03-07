<?php

namespace App\Features;

use App\Models\User;
use App\Traits\AllowTrait;

/*
|-------------------------------------------------------------------------------
| File Link Feature allows users to create unique public URL's to send or
| receive a file from someone without a Tech Bench login.
|-------------------------------------------------------------------------------
*/

class FileLinkFeature
{
    use AllowTrait;

    public function resolve(User $user): bool
    {
        if (! config('file-link.feature_enabled')) {
            return false;
        }

        return $this->checkPermission($user, 'Use File Links');
    }
}

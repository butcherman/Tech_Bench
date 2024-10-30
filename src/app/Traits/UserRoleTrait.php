<?php

namespace App\Traits;

use App\Facades\CacheFacade;
use App\Models\User;

trait UserRoleTrait
{
    /**
     * Get a list of user roles that the user is allowed to see.  By default
     * Users cannot view or modify a role with a higher Role ID than they have.
     */
    protected function getAvailableRoles(User $user): mixed
    {
        $roleList = CacheFacade::userRoles();

        return $roleList->where('role_id', '>=', $user->role_id);
    }
}

<?php

namespace App\Traits;

use App\Models\User;
use App\Models\UserRolePermissions;

/**
 *  AllowTrait only has one function to check permission for the policies
 */
trait AllowTrait
{
    protected function checkPermission(User $user, $description)
    {
        $allowed = UserRolePermissions::whereRoleId($user->role_id)->whereHas('UserRolePermissionTypes', function($q) use ($description)
        {
            $q->where('description', $description);
        })->first();

        if($allowed)
        {
            return $allowed->allow;
        }

        return false;
    }
}

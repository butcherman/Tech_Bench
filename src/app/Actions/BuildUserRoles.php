<?php

namespace App\Actions;

use App\Models\User;
use App\Service\Cache;

/**
 * This Action will return a list of roles that the logged in user can assign to new/edit users
 * This list is based on the logged in users current role.  They cannot assign a role with a
 * lower RoleId than they have access to.
 */
class BuildUserRoles
{
    public static function build(User $user)
    {
        $userRole = $user->role_id;
        $roleList = Cache::userRoles();

        switch ($userRole) {
            case 1:
                return $roleList->append('href');
            case 2:
                return $roleList->where('role_id', '>=', 2)->append('href');
            default:
                return $roleList->where('role_id', '>=', 2)->append('href');
        }
    }
}

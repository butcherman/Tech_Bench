<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

/**
 * This Action will return a list of roles that the logged in user can assign to new/edit users
 * This list is based on the logged in users current role.  They cannot assign a role with a
 * lower RoleId than they have access to.
 */
class GetAvailableUserRoles
{
    public function build(User $user)
    {
        $userRole = $user->role_id;
        $roleList = Cache::get('users.roles', BuildCacheData::buildRoleCache());

        switch ($userRole) {
            case 1:
                return $roleList;
            case 2:
                return $roleList->where('role_id', '>=', 2)->get();
            default:
                return $roleList->where('role_id', '>=', 2)->get();
        }
    }
}

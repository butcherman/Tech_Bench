<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserRolePermissions;
use App\Traits\AllowTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class GatePolicy
{
    use HandlesAuthorization;
    use AllowTrait;

    /**
     * Determine if the user is allowed to see the Administration navigation link
     */
    public function adminLink(User $user)
    {
        $userRole = UserRolePermissions::whereRoleId($user->role_id)->whereHas('UserRolePermissionTypes', function($q) {
            $q->whereIsAdminLink(1);
        })->whereAllow(1)->count();

        return $userRole == 0 ? false : true;
    }

    /**
     * Determine if the user is allowed to see the Reports navigation link
     */
    public function reportsLink(User $user)
    {
        if($this->checkPermission($user, 'Run Reports'))
        {
            return true;
        }

        return false;
    }
}

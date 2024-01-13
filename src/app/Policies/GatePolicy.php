<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserRolePermission;
use App\Traits\AllowTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class GatePolicy
{
    use AllowTrait;
    use HandlesAuthorization;

    /**
     * Determine if the user is allowed to see the Administration navigation link
     */
    public function adminLink(User $user)
    {
        $userRole = UserRolePermission::whereRoleId($user->role_id)
            ->whereHas('UserRolePermissionType', function ($q) {
                $q->whereIsAdminLink(1);
            })
            ->whereAllow(1)
            ->count();

        return $userRole == 0 ? false : true;
    }

    /**
     * Determine if the user is allowed to see the Reports navigation link
     */
    public function reportsLink(User $user)
    {
        if ($this->checkPermission($user, 'Run Reports')) {
            return true;
        }

        return false;
    }
}

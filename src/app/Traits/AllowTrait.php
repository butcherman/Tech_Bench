<?php

namespace App\Traits;

use App\Models\User;
use App\Models\UserRolePermission;

trait AllowTrait
{
    /**
     *  AllowTrait determines if a user can perform a task based on their Role
     */
    protected function checkPermission(User $user, $description): bool
    {
        $allowed = UserRolePermission::whereRoleId($user->role_id)
            ->whereHas('UserRolePermissionType', function ($q) use ($description) {
                $q->where('description', $description);
            })
            ->first();

        if ($allowed) {
            return (bool) $allowed->allow;
        }

        // @codeCoverageIgnoreStart
        return false;
        // @codeCoverageIgnoreEnd
    }

    /**
     * Determine if the user has access to any settings that would require them
     * to see the Administration menu.
     */
    protected function seeAdminLink(User $user): bool
    {
        $userRole = UserRolePermission::whereRoleId($user->role_id)
            ->whereHas('UserRolePermissionType', function ($q) {
                $q->whereIsAdminLink(1);
            })
            ->whereAllow(1)
            ->count();

        return $userRole == 0 ? false : true;
    }
}

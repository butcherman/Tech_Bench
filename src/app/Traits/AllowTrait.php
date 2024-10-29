<?php

namespace App\Traits;

use App\Models\User;
use App\Models\UserRolePermission;

/**
 *  AllowTrait only has one function to check permission for the policies
 */
trait AllowTrait
{
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
}

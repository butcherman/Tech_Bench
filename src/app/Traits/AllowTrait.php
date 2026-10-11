<?php

namespace App\Traits;

use App\Models\User;
use App\Models\UserRolePermission;
use Illuminate\Support\Collection;

/*
|-------------------------------------------------------------------------------
| AllowTrait determines if a user can perform a task based on their Role
|-------------------------------------------------------------------------------
*/

trait AllowTrait
{
    /**
     *  Check permission based on description field of permission type.
     */
    protected function checkPermission(User $user, string $description): bool
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
     * Return a list of users with permissions to the selected permission type
     * set to true.
     */
    protected function getUsersWithPermission(string $description): Collection
    {
        $allowedRoles = UserRolePermission::whereHas(
            'UserRolePermissionType',
            function ($q) use ($description) {
                $q->where('description', $description);
            }
        )->where('allow', true)->get()->pluck('role_id');

        return User::whereIn('role_id', $allowedRoles)->get();
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

    /**
     * Determine if the user has access to the Reporting Link
     */
    protected function seeReportLink(User $user): bool
    {
        return $this->checkPermission($user, 'Run Reports');
    }
}

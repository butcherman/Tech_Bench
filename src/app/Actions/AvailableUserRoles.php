<?php

namespace App\Actions;

use App\Models\User;
use App\Models\UserRole;
use App\Service\Cache;
use Illuminate\Database\Eloquent\Collection;

class AvailableUserRoles
{
    /**
     * This Action will return a list of roles that the logged in user can assign
     * to new/edit users.  This list is based on the logged in users current role.
     * They cannot assign a role with a lower RoleId than they have access to.
     *
     * @return Collection<int, UserRole>
     */
    public function get(User $user): Collection
    {
        $userRole = $user->role_id;
        $roleList = Cache::userRoles();

        return match ($userRole) {
            1 => $roleList->append('href'),
            2 => $roleList->where('role_id', '>=', 2)->append('href'),
            default => $roleList->where('role_id', '>=', 2)->append('href'),
        };
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserRolePermissions;
use App\Models\UserRolePermissionTypes;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class GatePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function adminLink(User $user)
    {
        $userRole = UserRolePermissions::whereRoleId($user->role_id)->whereHas('UserRolePermissionTypes', function($q)
        {
            $q->whereIsAdminLink(1);
        })->whereAllow(1)->count();

        return $userRole == 0 ? false : true;
    }
}

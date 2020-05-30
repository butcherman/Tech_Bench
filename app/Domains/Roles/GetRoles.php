<?php

namespace App\Domains\Roles;

use App\UserRolePermissionTypes;
use App\UserRoleType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class GetRoles
{
    //  Gather a list of roles available  if the limit is turned on, installer and admin roles will not be returned for lower level users
    public function getRoleList($limit = true)
    {
        $roles = UserRoleType::with('UserRolePermissions')->get();

        if($limit)
        {
            if(Auth::user()->role_id !== 1)
            {
                $roles->forget(0);
            }

            if(Auth::user()->role_id > 2)
            {
                $roles->forget(1);
            }
        }

        Log::debug('Role list gathered.  Data - ', $roles->toArray());
        return $roles;
    }
}

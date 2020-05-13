<?php

namespace App\Domains\Users;

use Illuminate\Support\Facades\Auth;

use App\Http\Resources\UserRoleTypeCollection;

use App\UserRoleType;

class GetUserRoles
{
    //  Gather a list of roles available  if the limit is turned on, installer and admin roles will not be returned for lower level users
    public function getRoleList($limit = true)
    {
        $roles = new UserRoleTypeCollection(UserRoleType::all());

        if($limit)
        {
            if(Auth::user()->role_id !== 1)
            {
                $roles->forget(0);
            }

            //TOTO - Test this and make sure it is working
            if(Auth::user()->role_id > 2)
            {
                $roles->forget(1);
            }
        }

        return $roles;
    }
}

<?php

namespace App\Actions;

use App\Models\User;
use App\Models\UserRoles;

class GetUserRoles
{
    public function run(User $user)
    {
        // $userRole = $user->role_id;

        // switch($userRole)
        // {
        //     case 1:
        //         $roles = UserRoles::all();
        //         break;
        //     case 2:
        //         $roles = UserRoles::where('role_id', '>=', 2)->get();
        //         break;
        //     default:
        //         $roles = UserRoles::where('role_id', '>', 2)->get();
        // }

        // return $roles;
    }
}

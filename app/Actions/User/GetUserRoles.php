<?php

namespace App\Actions\User;

use App\Models\UserRoles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class GetUserRoles
{
    //  Determine which list of user roles the user is allowed to assign to a user
    public function run($user)
    {
        $userRole = $user->role_id;

        switch($userRole)
        {
            case 1:
                $roles = UserRoles::all();
                break;
            case 2:
                $roles = UserRoles::where('role_id', '>=', 2)->get();
                break;
            default:
                $roles = UserRoles::where('role_id', '>', 2)->get();
        }

        return $roles;
    }
}

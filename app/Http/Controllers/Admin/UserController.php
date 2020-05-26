<?php

namespace App\Http\Controllers\Admin;

use App\Domains\User\GetUserDetails;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //  Check if a username is in use
    public function checkUser($username, $type)
    {
        $user = (new GetUserDetails)->checkForDuplicate($type, $username);

        if(!$user)
        {
            return response()->json(['duplicate' => false]);
        }

        return response()->json([
            'duplicate' => true,
            'user'      => $user->full_name,
            'username'  => $user->username,
            'active'    => $user->deleted_at == null ? 1 : 0,
        ]);
    }
}

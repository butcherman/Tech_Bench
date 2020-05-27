<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Domains\Roles\GetRoles;
use App\Domains\User\GetUserDetails;
use App\Domains\User\SetUserDetails;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewUserRequest;
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

    //  Show the add user form
    public function create()
    {
        return view('admin.newUser', [
            'roles' => (new GetRoles)->getRoleList()->makeHidden('allow_edit'),
        ]);
    }

    //  Submit the add user form
    public function store(NewUserRequest $request)
    {
        $newID = (new SetUserDetails)->createUser($request);
        Log::notice('New user created by '.Auth::user()->full_name.'. New User ID - '.$newID.'. User Data - ', $request->toArray());

        return response()->json(['success' => true]);
    }
}

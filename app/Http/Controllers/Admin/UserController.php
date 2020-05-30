<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Domains\Roles\GetRoles;
use App\Domains\User\GetUserDetails;
use App\Domains\User\GetUserList;
use App\Domains\User\SetUserDetails;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangeUserPasswordRequest;
use App\Http\Requests\Admin\EditUserRequest;
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
            'roles' => (new GetRoles)->getRoleList()->makeHidden(['allow_edit', 'user_role_permissions']),
        ]);
    }

    //  Submit the add user form
    public function store(NewUserRequest $request)
    {
        $newID = (new SetUserDetails)->createUser($request);
        Log::notice('New user created by '.Auth::user()->full_name.'. New User ID - '.$newID.'. User Data - ', $request->toArray());

        return response()->json(['success' => true]);
    }

    //  List all active users
    public function listActive()
    {
        return view('admin.userList', [
            'userList' => (new GetUserList)->getActiveUsers(),
            'active'   => true,
        ]);
    }

    //  List all users who have been disabled
    public function listInactive()
    {
        return view('admin.userList', [
            'userList' => (new GetUserList)->getInactiveUsers(),
            'active'   => false,
        ]);
    }

    //  Form to edit an existing user
    public function edit($userID)
    {
        //  Before showing user form, verify that the user does not have more permission
        $user = (new GetUserDetails($userID))->getUserData()->makeVisible(['role_id', 'user_id']);
        if($user->role_id < Auth::user()->role_id)
        {
            return abort(403, 'You cannot update a user with more permissions than you');
        }

        return view('admin.userEdit', [
            'details' => $user,
            'roles' => (new GetRoles)->getRoleList()->makeHidden(['allow_edit', 'user_role_permissions']),
        ]);
    }

    //  Submit the edit user form
    public function update(EditUserRequest $request, $userID)
    {
        //  Before submitting user form, verify that the user does not have more permission
        $user = (new GetUserDetails($userID))->getUserData()->makeVisible(['role_id', 'user_id']);
        if($user->role_id < Auth::user()->role_id)
        {
            return abort(403, 'You cannot update a user with more permissions than you');
        }

        (new SetUserDetails)->updateUser($request, $userID);
        Log::info('User ID '.$userID.' was update by '.Auth::user()->full_name.'.  Details - ', $request->toArray());
        return response()->json(['success' => true]);
    }

    //  Submit the change password form
    public function changePassword(ChangeUserPasswordRequest $request)
    {
        //  Before changing user password, verify that the user does not have more permission
        $user = (new GetUserDetails($request->user_id))->getUserData();
        if($user->role_id < Auth::user()->role_id)
        {
            return abort(403, 'You cannot update a user with more permissions than you');
        }

        (new SetUserDetails)->updatePassword($request->password, $request->user_id, $request->force_change);
        Log::info('Password for User ID '.$request->user_id.' has been updated by '.Auth::user()->full_name);

        return response()->json(['success' => true]);
    }

    //  See the login history for the user
    public function loginHistory($userID, $username)
    {
        return $userID;
    }

    //  Deactivate a user
    public function destroy($userID)
    {
        //  Before disabling user, verify that the user does not have more permission
        $user = (new GetUserDetails($userID))->getUserData();
        if($user->role_id < Auth::user()->role_id)
        {
            return abort(403, 'You cannot disable a user with more permissions than you');
        }

        (new SetUserDetails)->disableUser($userID);
        Log::notice('User '.$user->full_name.' has been disabled by '.Auth::user()->full_name.'.  Details - ', $user->toArray());
        return response()->json(['success' => true]);
    }

    //  Reactivate a disabled user
    public function activate($userID)
    {
        (new SetUserDetails)->reactivateUser($userID);
        Log::notice('User ID '.$userID.' has been reactivated by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }
}

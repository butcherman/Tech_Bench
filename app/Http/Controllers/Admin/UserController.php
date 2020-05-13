<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Users\UserList;
use App\Domains\Users\GetUserDetails;
use App\Domains\Users\GetUserRoles;
use App\Domains\Users\SetUserDetails;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserChangePasswordRequest;
use App\Http\Requests\UserBasicAccountRequest;
use App\Http\Requests\UserCreateRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UserCollection;


class UserController extends Controller
{
    //  Constructor sets up middleware
    public function __construct()
    {
        $this->middleware('auth')->except('initializeUser', 'submitInitializeUser');
        $this->middleware(function($request, $next) {
            $this->authorize('hasAccess', 'Manage Users');
            return $next($request);
        });
    }

    //  Show the list of current users to edit
    public function index()
    {
        return view('admin.userIndex', [
            'userList' => (new UserList)->getActiveUsers()->toJson(),
        ]);
    }

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

    //  Show the Add User form
    public function create()
    {
        return view('admin.newUser', [
            'roles' => (new GetUserRoles)->getRoleList()->toJson(),
        ]);
    }

    //  Submit the Add User form
    public function store(UserCreateRequest $request)
    {
        (new SetUserDetails)->createNewUser($request);
        return response()->json(['success' => true]);
    }










    //  List all inactive users
    public function show($type)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        $route = '';

        if($type !== 'inactive')
        {
            Log::error('Someone tried to access the Inactive Users link with an improper argument - Argument: '.$type);
            return abort(404);
        }
        $userList = new UserCollection(User::onlyTrashed()->get()
                /** @scrutinizer ignore-call */
                ->makeVisible('user_id')
                ->makeVisible('deleted_at'));

        Log::debug('List of inactive users - ', array($userList));
        return view('admin.userDeleted', [
            'userList' => $userList,
            'route'    => $route,
        ]);
    }

    //  Reactivate a disabled user
    public function reactivateUser($id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        User::withTrashed()->where('user_id', $id)->restore();

        Log::notice('User ID '.$id.' reactivated by '.Auth::user()->full_name);
        return response()->json([
            'success' => true,
        ]);
    }









    //  Open the edit user form
    public function edit($id)
    {
        $userObj = new GetUserDetails($id);
        $details = $userObj->getuserData()->makeVisible(['user_id', 'username', 'role_id']);
        if($details->role_id < Auth::user()->role_id)
        {
            abort(403, 'You cannot edit a user with more permissions than you');
        }

        return view('admin.userEdit', [
            'roles' => (new GetUserRoles)->getRoleList()->toJson(),
            'user'  => $details->toJson(),
        ]);
    }

    //  Submit the update user form
    public function update(UserBasicAccountRequest $request, $id)
    {
        $userObj = new SetUserDetails;
        $userObj->updateUserDetails($request, $id);

        return response()->json(['success' => true]);
    }

    //  Submit the change password form
    public function submitPassword(AdminUserChangePasswordRequest $request)
    {
        $userObj = new SetUserDetails;
        $userObj->updateUserPassword($request->password, $request->user_id, $request->force_change);

        return response()->json([
            'success' => true,
            'reason'  => 'Password successfully reset',
        ]);
    }

    //  Disable the user
    public function destroy($id)
    {
        $userObj = new SetUserDetails;
        $userObj->disableUser($id);

        return response()->json([
            'success' => true,
            'reason'  => 'User successfully deactivated',
        ]);
    }
}

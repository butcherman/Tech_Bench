<?php

namespace App\Http\Controllers\Admin;

use DB;
use Mail;
// use App\Role;
use App\User;
use Carbon\Carbon;
use App\UserInitialize;
use Illuminate\Support\Str;
use App\Mail\InitializeUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewUserEmail;

use App\UserRoleType;
use App\UserLogins;
use App\Http\Resources\UserCollection;
use App\Http\Resources\User as UserResource;

class UserController extends Controller
{
    //  Constructor sets up middleware
    public function __construct()
    {
        $this->middleware('auth')->except('initializeUser', 'submitInitializeUser');
        $this->middleware(function ($request, $next) {
            $this->authorize('hasAccess', 'Manage Users');
            return $next($request);
        });
    }

    //  Show the list of current users to edit
    public function index()
    {
        $userList = new UserCollection(User::with(['UserLogins' => function ($query) {
            $query->latest()->limit(1);
        }])->get()
            /** @scrutinizer ignore-call */
            ->makeVisible('user_id'));
        $route    = 'admin.user.edit';

        return view('admin.userIndex', [
            'userList' => $userList,
            'route'    => $route,
        ]);
    }

    //  Check if a username is in use
    public function checkUser($username, $type)
    {
        $user = User::where($type, $username)->first();

        if(!$user)
        {
            return response()->json(['duplicate' => false]);
        }

        return response()->json([
            'duplicate' => true,
            'user'      => $user->full_name,
            'active'    => $user->deleted_at == null ? 1 : 0,
        ]);
    }

    //  Show the Add User form
    public function create()
    {
        $roles = UserRoleType::all(); // Role::all();

        $roleArr = [];
        foreach($roles as $role)
        {
            if($role->role_id == 1 && Auth::user()->role_id != 1)
            {
                continue;
            }
            else if($role->role_id == 2 && Auth::user()->role_id > 1)
            {
                continue;
            }
            else
            {
                // $roleArr[$role->role_id] = $role->name;
                $roleArr[] = [
                    'value' => $role->role_id,
                    'text'  => $role->name,
                ];
            }
        }

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('admin.newUser', [
            'roles' => $roleArr
        ]);
    }

    //  Submit the Add User form
    public function store(Request $request)
    {
        //  Validate the new user form
        $request->validate([
            'role'       => 'required|numeric',  //  TODO - add validation rule - is in user roles table
            'username'   => 'required|unique:users|regex:/^[a-zA-Z0-9_]*$/',
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|unique:users',
        ]);

        //  Create the user
        $newUser = User::create([
            'role_id'    => $request->role,
            'username'   => $request->username,
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => bcrypt(strtolower(Str::random(15))),
        ]);

        $userID = $newUser->user_id;

        //  Create the setup user link
        $hash = strtolower(Str::random(30));
        UserInitialize::create([
            'username' => $request->username,
            'token'    => $hash
        ]);

        //  Email the new user
        // Mail::to($request->email)->send(new InitializeUser($hash, $request->username, $request->first_name.' '.$request->last_name));
        Notification::send($newUser, new NewUserEmail($newUser, $hash));

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('User Data - ', $newUser->toArray());
        Log::notice('New User ID-'.$userID.' Created by ID-'.Auth::user()->user_id);

        // return redirect()->back()->with('success', 'New User Created');
        return response()->json(['success' => true]);
    }

    //  List all inactive users
    public function show($type)
    {
        $route    = '';

        if($type !== 'inactive')
        {
            return abort(404);
        }
        $userList = new UserCollection(User::onlyTrashed()->get()
                /** @scrutinizer ignore-call */
                ->makeVisible('user_id'));

        return view('admin.userDeleted', [
            'userList' => $userList,
            'route'    => $route,
        ]);

    }

    //  Open the edit user form
    public function edit($id)
    {
        $roles = UserRoleType::all(); // Role::all();
        $user  = new UserResource(User::findOrFail($id));

        //  Make sure that the user is not trying to deactivate someone with more permissions
        if ($user->role_id < Auth::user()->role_id)
        {
            return abort(403);
        }

        //  Good to go - update user password
        $roleArr = [];
        foreach ($roles as $role) {
            if ($role->role_id == 1 && Auth::user()->role_id != 1) {
                continue;
            } else if ($role->role_id == 2 && Auth::user()->role_id > 1) {
                continue;
            } else {
                // $roleArr[$role->role_id] = $role->name;
                $roleArr[] = [
                    'value' => $role->role_id,
                    'text'  => $role->name,
                ];
            }
        }

        Log::debug('Route ' . Route::currentRouteName() . ' visited by User ID-' . Auth::user()->user_id);
        return view('admin.userEdit', [
            'roles' => $roleArr,
            'user'  => $user->
            /** @scrutinizer ignore-call */
            makeVisible(['user_id', 'username']),
        ]);
    }

    //  Reactivate a disabled user
    public function reactivateUser($id)
    {
        User::withTrashed()->where('user_id', $id)->restore();

        return response()->json([
            'success' => true,
        ]);
    }

    //  Submit the update user form
    public function update(Request $request, $id)
    {
        $request->validate([
            'username'   => [
                                'required',
                                Rule::unique('users')->ignore($id, 'user_id')
                            ],
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => [
                                'required',
                                Rule::unique('users')->ignore($id, 'user_id')
                            ],
            'role'       => 'required',
        ]);

        //  Update the user data
        $user = User::findOrFail($id);

        if ($user->role_id < Auth::user()->role_id)
        {
            return abort(403);
        }

        $user->update(
        [
            'username'   => $request->username,
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'role_id'    => $request->role,
        ]);

        //  Update the user's role
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Edit user form submitted for User ID-'.$id.'  Data - ', $request->toArray());
        Log::notice('User ID-'.$id.' has updated their information.');
        return response()->json(['success' => true]);
    }

    //  Submit the change password form
    public function submitPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
            'user_id'  => 'required',
        ]);

        // $nextChange = isset($request->force_change) && $request->force_change == 'on' ? Carbon::now()->subDay() : null;

        if($request->force_change)
        {
            $nextChange = Carbon::now()->subDay();
        }
        else
        {
            $nextChange = config('users.passExpires') != null ? Carbon::now()->addDays(config('users.passExpires')) : null;
        }

        $user = User::find($request->user_id);

        //  Verify this is a valid user ID
        if (!$user) {
            $success = false;
            $reason  = 'Cannot find user with this ID';
        }
        //  Make sure that the user is not trying to deactivate someone with more permissions
        else if ($user->role_id < Auth::user()->role_id) {
            $success = false;
            $reason  = 'You cannot change password for a user with higher permissions that you.  If this user has locked themselves out, have then use the reset link on the login page.';
        }
        //  Good to go - update user password
        else {
            //  Update the user data
            $user->update(
            [
                'password'         => bcrypt($request->password),
                'password_expires' => $nextChange
            ]);
            $success = true;
            $reason  = 'Password for ' . $user->full_name . ' successfully reset.';
        }

        Log::debug('Route ' . Route::currentRouteName() . ' visited by User ID-' . Auth::user()->user_id);
        Log::notice('User ID-' . $request->user_id . ' password chagned by ' . Auth::user()->user_id, [
            'success' => $success,
            'reason'  => $reason,
        ]);

        return response()->json([
            'success' => $success,
            'reason'  => $reason,
        ]);
    }

    //  Disable the user
    public function destroy($id)
    {
        $user = User::find($id);

        //  Verify this is a valid user ID
        if(!$user)
        {
            $success = false;
            $reason  = 'Cannot find user with this ID';
        }
        //  Make suer that the user is not trying to deactivate themselves
        else if(Auth::user()->user_id == $id)
        {
            $success = false;
            $reason  = 'You cannot deactivate yourself';
        }
        //  Make sure that the user is not trying to deactivate someone with more permissions
        else if($user->role_id < Auth::user()->role_id)
        {
            $success = false;
            $reason  = 'You cannot deactivate a user with higher permissions that you.';
        }
        //  Good to go - deactivate user
        else
        {
            // $user->update(['active' => 0]);
            $user->delete();
            $success = true;
            $reason  = 'User '.$user->full_name.' successfully deactivated.';
        }

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::notice('User ID-'.$id.' disabled by '.Auth::user()->user_id, [
            'success' => $success,
            'reason'  => $reason,
        ]);

        return response()->json([
            'success' => $success,
            'reason'  => $reason,
        ]);
    }
}

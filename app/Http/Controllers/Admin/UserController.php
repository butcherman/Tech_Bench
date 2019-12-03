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
    private $user;
    //  Constructor sets up middleware
    public function __construct()
    {
        $this->middleware('auth')->except('initializeUser', 'submitInitializeUser');
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();  //  TODO - is this correct????
            $this->authorize('hasAccess', 'Manage Users');
            return $next($request);
        });
    }

    //  Show the list of current users to edit
    public function index()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('admin.userIndex', [
            'link' => 'admin.user.edit'
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
            'active'    => $user->active,
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
            'active'     => 1
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























    //  List all active or inactive users
    public function show($type)
    {
        switch($type)
        {
            case 'active':
                $userList = new UserCollection(User::where('active', 1)->with(['UserLogins' => function($query)
                {
                    $query->latest()->limit(1);
                }])->get()->makeVisible('user_id'));
                $route    = 'admin.user.edit';
                break;
            default:
                abort(404);
        }

        // return $userList;


        return view('admin.userIndex', [
            'userList' => $userList,
            'route'    => $route,
            // 'method'   => 'edit',
        ]);

    }

    //  Open the edit user form
    public function edit($id)
    {
        //  TODO - cannot edit a user with better permissions than current user

        $roles = UserRoleType::all(); // Role::all();
        $user  = new UserResource(User::find($id));

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
            'user'  => $user->makeVisible(['user_id', 'username']),
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
        User::find($id)->update(
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















    //  List the active users to change the password for
    public function passwordList()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('admin.userIndex', [
            'link' => 'admin.changePassword'
        ]);
    }

    //  Change password form
    public function changePassword($id)
    {
        $name = User::find($id);
        $name = $name->first_name.' '.$name->last_name;

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Change change password form opened for User ID-'.$id);
        return view('admin.changePassword', [
            'id'   => $id,
            'user' => $name
        ]);
    }

    //  Submit the change password form
    public function submitPassword(Request $request, $id)
    {
        $request->validate([
            'password'   => 'required|string|min:6|confirmed'
        ]);

        $nextChange = isset($request->force_change) && $request->force_change == 'on' ? Carbon::now()->subDay() : null;

            //  Update the user data
        User::find($id)->update(
        [
            'password'         => bcrypt($request->password),
            'password_expires' => $nextChange
        ]);

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Password Change form submitted for user ID-'.$id.' Data - ', $request->toArray());
        Log::info('User ID-'.$id.' has changed their password.');
        return redirect(route('admin.user.index'))->with('success', 'User Password Updated Successfully');
    }

    //  Bring up the users that are available to deactivate
    public function disable()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('admin.userIndex', [
            'link' => 'admin.confirmDisable'
        ]);
    }

    //  Confirm to disable the user
    public function confirm($id)
    {
        $name = User::find($id);
        $name = $name->first_name.' '.$name->last_name;

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('admin.disableUser', [
            'id'   => $id,
            'name' => $name
        ]);
    }

    //  Disable the user
    public function destroy($id)
    {
        User::find($id)->update([
            'active' => 0
        ]);

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::notice('User ID-'.$id.' disabled by '.Auth::user()->user_id);

        return redirect(route('admin.user.index'))->with('success', 'User Deactivated Successfully');
    }
}

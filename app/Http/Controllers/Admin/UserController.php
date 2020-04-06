<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Carbon\Carbon;
use App\UserSettings;
use App\UserRoleType;
use App\UserInitialize;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Notifications\NewUserEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UserCollection;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Notification;

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
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);

        $userList = User::with('LastUserLogin')->get()->makeVisible('user_id');
        $route    = 'admin.user.edit';

        Log::debug('User list:', $userList->toArray());

        return view('admin.userIndex', [
            'userList' => $userList,
            'route'    => $route,
        ]);
    }

    //  Check if a username is in use
    public function checkUser($username, $type)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data:', ['username' => $username, 'type' => $type]);

        $user = User::where($type, $username)->first();

        if(!$user)
        {
            Log::debug('Username '.$username.' is available for use');
            return response()->json(['duplicate' => false]);
        }

        Log::debug('Username '.$username.' is in use by '.$user->full_name);
        return response()->json([
            'duplicate' => true,
            'user'      => $user->full_name,
            'active'    => $user->deleted_at == null ? 1 : 0,
        ]);
    }

    //  Show the Add User form
    public function create()
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);

        $roles = UserRoleType::all();
        $roleArr = [];
        //  Cycle through the roles and determine if admin and installer roles should be removed
        foreach($roles as $role)
        {
            if($role->role_id == 1 && Auth::user()->role_id != 1)
            {
                Log::debug('Installer Role skipped for User '.Auth::user()->full_name);
                continue;
            }
            else if($role->role_id == 2 && Auth::user()->role_id > 2)
            {
                Log::debug('Admin Role skipped for User '.Auth::user()->full_name);
                continue;
            }
            else
            {
                $roleArr[] = [
                    'value' => $role->role_id,
                    'text'  => $role->name,
                ];
            }
        }

        Log::debug('Role data gathered: ', $roleArr);
        return view('admin.newUser', [
            'roles' => $roleArr
        ]);
    }

    //  Submit the Add User form
    public function store(Request $request)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data:', $request->toArray());

        //  Validate the new user form
        $request->validate([
            'role'       => 'required|numeric|exists:user_role_types,role_id',
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
        Log::debug('New User created.  Data - ', $newUser->toArray());
        //  Create the user settings table
        UserSettings::create([
            'user_id' => $userID,
        ]);
        Log::debug('User Settings table created for user ID '.$userID);

        //  Create the setup user link
        $hash = strtolower(Str::random(30));
        UserInitialize::create([
            'username' => $request->username,
            'token'    => $hash
        ]);
        Log::debug('User Initialize link created for User ID '.$userID.'. New Link Hash - '.$hash);

        //  Email the new user
        Notification::send($newUser, new NewUserEmail($newUser, $hash));

        Log::notice('New user '.$newUser->first_name.' '.$newUser->last_name.' created by '.Auth::user()->full_name.'. User Data:', $newUser->toArray());

        // return redirect()->back()->with('success', 'New User Created');
        return response()->json(['success' => true]);
    }

    //  List all inactive users
    public function show($type)
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);
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
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);
        User::withTrashed()->where('user_id', $id)->restore();

        Log::notice('User ID ' . $id . ' reactivated by ' . Auth::user()->full_name);
        return response()->json([
            'success' => true,
        ]);
    }

    //  Open the edit user form
    public function edit($id)
    {
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);

        $roles = UserRoleType::all();
        $user  = new UserResource(User::findOrFail($id));

        //  Make sure that the user is not trying to edit someone with more permissions
        if(($user->role_id == 1 || $user->role_id == 2) && Auth::user()->role_id <=2)
        {
            Log::warning('User '.Auth::user()->full_name.' tried to update user ID '.$id.' that has more permissions than they do.  This request was denied.');
            return abort(403);
        }

        Log::debug('User Data gathered - ', array($user));

        //  Good to go - get role information
        $roleArr = [];
        foreach ($roles as $role)
        {
            if ($role->role_id == 1 && Auth::user()->role_id != 1)
            {
                continue;
            }
            else if ($role->role_id == 2 && Auth::user()->role_id > 2)
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

        Log::debug('Role Data gathered:', $roleArr);
        return view('admin.userEdit', [
            'roles' => $roleArr,
            'user'  => $user->
            /** @scrutinizer ignore-call */
            makeVisible(['user_id', 'username']),
        ]);
    }

    //  Submit the update user form
    public function update(Request $request, $id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data:', $request->toArray());

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
        if($user->role_id < Auth::user()->role_id)
        {
            Log::warning('User ' . Auth::user()->full_name . ' tried to update user ID ' . $id . ' that has more permissions than they do.  This request was denied.');
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
        Log::info('User information for '.$request->first_name.' '.$request->last_name.' (ID: '.$id.') has been updated by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }

    //  Submit the change password form
    public function submitPassword(Request $request)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
            'user_id'  => 'required',
        ]);

        if($request->force_change)
        {
            $nextChange = Carbon::now()->subDay();
        }
        else
        {
            $nextChange = config('auth.passwords.settings.expire') != null ? Carbon::now()->addDays(config('auth.passwords.settings.expire')) : null;
        }

        $user = User::find($request->user_id);

        //  Verify this is a valid user ID
        if (!$user)
        {
            $success = false;
            $reason  = 'Cannot find user with this ID';
        }
        //  Make sure that the user is not trying to deactivate someone with more permissions
        else if ($user->role_id < Auth::user()->role_id)
        {
            $success = false;
            $reason  = 'You cannot change password for a user with higher permissions that you.  If this user has locked themselves out, have then use the reset link on the login page.';
        }
        //  Good to go - update user password
        else
        {
            //  Update the user data
            $user->update(
            [
                'password'         => bcrypt($request->password),
                'password_expires' => $nextChange
            ]);
            $success = true;
            $reason  = 'Password for '.$user->full_name.' successfully reset.';
        }

        Log::notice('User ID-'.$request->user_id.' password chagned by '.Auth::user()->Full_name, [
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
        Log::debug('Route ' . Route::currentRouteName() . ' visited by ' . Auth::user()->full_name);

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

        Log::notice('User ID-'.$id.' disabled by '.Auth::user()->full_name, [
            'success' => $success,
            'reason'  => $reason,
        ]);

        return response()->json([
            'success' => $success,
            'reason'  => $reason,
        ]);
    }
}

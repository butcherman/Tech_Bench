<?php

namespace App\Http\Controllers\Admin;

use DB;
use Mail;
use App\Role;
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

class UserController extends Controller
{
    //  Constructor sets up middleware
    public function __construct()
    {
        $this->middleware('auth')->except('initializeUser', 'submitInitializeUser');
    }
    
    //  Show the list of current users to edit
    public function index()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('admin.userIndex', [
            'link' => 'admin.user.edit'
        ]);
    }

    //  Show the Add User form
    public function create()
    {
        $roles = Role::all();

        $roleArr = [];
        foreach($roles as $role)
        {
            if($role->role_id == 1 && !Auth::user()->hasAnyRole(['installer']))
            {
            
                continue;
            }
            else
            {
                $roleArr[$role->role_id] = $role->name;
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
            'username'   => 'required|unique:users|regex:/^[a-zA-Z0-9_]*$/',
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|unique:users',
        ]);
        
        //  Create the user
        $newUser = User::create([
            'username'   => $request->username,
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => bcrypt(strtolower(Str::random(15))),
            'active'     => 1
        ]);
        
        $userID = $newUser->user_id;
        
        //  Assign the users role
        DB::insert('INSERT INTO `user_role` (`user_id`, `role_id`) VALUES (?, ?)', [$userID, $request->role]);
        
        //  Create the setup user link
        $hash = strtolower(Str::random(30));
        UserInitialize::create([
            'username' => $request->username,
            'token'    => $hash
        ]);
        
        //  Email the new user
        Mail::to($request->email)->send(new InitializeUser($hash, $request->username, $request->first_name.' '.$request->last_name));
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('User Data - ', $newUser->toArray());
        Log::notice('New User ID-'.$userID.' Created by ID-'.Auth::user()->user_id);
        
        return redirect()->back()->with('success', 'New User Created');
    }
    
    //  Bring up the "Finish User Setup" form
    public function initializeUser($hash)
    {
        $this->middleware('guest');
        
        //  Validate the hash token
        $user = UserInitialize::where('token', $hash)->get();
        
        if($user->isEmpty())
        {
            Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
            Log::warning('Visitor at IP Address '.\Request::ip().' tried to access invalid initialize hash - '.$hash);
            return abort(404);
        }
        
        Log::debug('Route '.Route::currentRouteName().' visited.');
        Log::debug('Link Hash -'.$hash);
        return view('account.initializeUser', ['hash' => $hash]);
    }
    
    //  Submit the initialize user form
    public function submitInitializeUser(Request $request, $hash)
    {
        //  Verify that the link matches the assigned email address
        $valid = UserInitialize::where('token', $hash)->first();
        if(empty($valid))
        {
            Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
            Log::warning('Visitor at IP Address '.\Request::ip().' tried to submit an invalid User Initialization link - '.$hash);
            return abort(404);
        }
        
        //  Validate the form
        $request->validate([
            'username' => [
                'required',
                Rule::in([$valid->username]),
            ],
            'newPass'  => 'required|string|min:6|confirmed'
        ]);
        
        //  Get the users information
        $userData = User::where('username', $valid->username)->first();
        
        $nextChange = config('users.passExpires') != null ? Carbon::now()->addDays(config('users.passExpires')) : null;
        
            //  Update the password
        User::find($userData->user_id)->update(
        [
            'password'         => bcrypt($request->newPass),
            'password_expires' => $nextChange
        ]);
        
        //  Remove the initialize instance
        UserInitialize::find($valid->id)->delete();
        
        //  Log in the user
        Auth::loginUsingID($userData->user_id);
        
        //  Redirect the user to the dashboard
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Initialize Data - '.$request->toArray());
        Log::notice('User has setup account', ['user_id' => $userData->user_id]);
        return redirect(route('dashboard'));
    }

    //  List all active or inactive users
    public function show($type)
    {
        $res = '';
        if($type == 'active')
        {
            $res = User::where('active', true)->with('UserLogins')->get();
        }
        
        $userList = [];
        foreach($res as $r)
        {
            $userList[] = [
                'user_id' => $r->user_id,
                'user'    => $r->first_name.' '.$r->last_name,
                'email'   => $r->email,
                'last'    => $r->UserLogins->last() ? date('M j, Y - g:i A', strtotime($r->UserLogins->last()->created_at)) : 'Never'
            ];
        }
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('User List - ', $userList);
        return response()->json($userList);
    }

    //  Open the edit user form
    public function edit($id)
    {
        $roles    = Role::all();
        $userData = User::find($id);
        $userRole = DB::select('SELECT `role_id` FROM `user_role` WHERE `user_id` = ?', [$id])[0]->role_id;
        
        $roleArr = [];
        foreach($roles as $role)
        {
            if($role->role_id == 1 && !Auth::user()->hasAnyRole(['installer']))
            {
                continue;
            }
            else
            {
                $roleArr[$role->role_id] = $role->name;
            }
        }
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Edit user form opened for user ID-'.$id);
        return view('admin.editUser', [
            'userID' => $id,
            'roles'  => $roleArr,
            'role'   => $userRole,
            'user'   => $userData
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
        ]);
        
        //  Update the user data
        User::find($id)->update(
        [
            'username'   => $request->username,
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email
        ]);
        
        //  Update the user's role
        DB::update('UPDATE `user_role` SET `role_id` = ? WHERE `user_id` = ?', [$request->role, $id]);
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Edit user form submitted for User ID-'.$id.'  Data - ', $request->toArray());
        Log::notice('User ID-'.$id.' has updated their information.');
        return redirect(route('admin.user.index'))->with('success', 'User Updated Successfully');
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

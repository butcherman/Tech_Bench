<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use DB;
use App\Role;
use App\User;
use App\UserLogins;
use App\UserInitialize;
use App\Mail\InitializeUser;
use App\Http\Controllers\FileLinksController;
use App\FileLinks;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('initializeUser', 'submitInitializeUser');
    }
    
    //  Show the list of current users
    public function index()
    {
        $users = User::where('active', 1)->with('UserLogins')->get();

        return view('admin.userList', [
            'users' => $users
        ]);
    }

    //  New user form
    public function create()
    {
        $roles = Role::all();
        
        $roleArr = [];
        foreach($roles as $role)
        {
            if($role->role_id == 1 && Auth::user()->role_id != 1)
            {
                continue;
            }
            else
            {
                $roleArr[$role->role_id] = $role->name;
            }
        }
        
        return view('admin.form.newUser', [
            'roles' => $roleArr
        ]);
    }

    //  Create the new user
    public function store(Request $request)
    {
        //  Validate the new user form
        $request->validate([
            'username'   => 'required|unique:users|regex:/^[a-zA-Z0-9_]*$/',
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|unique:users',
//            'password'   => 'required|string|min:6|confirmed'
        ]);
        
        //  Create the user
        $newUser = User::create([
            'username'   => $request->username,
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => bcrypt(strtolower(str_random(15))),
            'active'     => 1
        ]);
        
        $userID = $newUser->user_id;
        
        //  Assign the users role
        DB::insert('INSERT INTO `user_role` (`user_id`, `role_id`) VALUES (?, ?)', [$userID, $request->role]);
        
        //  Create the setup user link
        $hash = strtolower(str_random(30));
        UserInitialize::create([
            'username' => $request->username,
            'token'    => $hash
        ]);
        
        //  Email the new user
        try
        {
            Mail::to($request->email)->send(new InitializeUser($hash, $request->username, $request->first_name.' '.$request->last_name));
        }
        catch(Exception $e)
        {
            report($e);
        }
        
        Log::info('New User ID-'.$userID.' Created by ID-'.Auth::user()->user_id);
        
        return redirect(route('admin.users.index'))->with('success', 'User Created Successfully');
    }
    
    //  Finish setting up the user account by making them assign their password
    public function initializeUser($hash)
    {
        $this->middleware('guest');
        
        //  Validate the hash token
        $user = UserInitialize::where('token', $hash)->get();
        
        if($user->isEmpty())
        {
            return abort(404);
        }
        
        return view('account.form.initializePassword', ['hash' => $hash]);
    }
    
    //  Submit the password change and log in the user
    public function submitInitializeUser($hash, Request $request)
    {
        //  Verify that the link matches the assigned email address
        $valid = UserInitialize::where('token', $hash)->first();
        if(empty($valid))
        {
            Log::notice('Someone tried to access an invalid User Initialization link - '.$hash);
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
        
        //  Log the change
        Log::info('User has setup account', ['user_id' => $userData->user_id]);
        
        //  Log in the user
        Auth::loginUsingID($userData->user_id);
        
        //  Redirect the user to the dashboard
        Log::info('New User ID-'.$userData->user_id.' has updated their password.');
        return redirect(route('dashboard'));
    }

    //  Show the for to edit a users password
    public function show($id)
    {
        $name = User::find($id);
        $name = $name->first_name.' '.$name->last_name;
        return view('admin.form.resetPassword', [
            'id'   => $id,
            'user' => $name
        ]);
    }
    
    //  Submit the reset password form
    public function resetPassword(Request $request, $id)
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

        Log::info('User ID-'.$id.' has changed their password.');
        return redirect(route('admin.users.index'))->with('success', 'User Password Updated Successfully');
    }

    //  Edit an existing user
    public function edit($id)
    {
        $roles = Role::all();
        $userData = User::find($id);
        $userRole = DB::select('SELECT `role_id` FROM `user_role` WHERE `user_id` = ?', [$id])[0]->role_id;
        
        $roleArr = [];
        foreach($roles as $role)
        {
            if($role->role_id == 1 && Auth::user()->role_id != 1)
            {
                continue;
            }
            else
            {
                $roleArr[$role->role_id] = $role->name;
            }
        }
        
        return view('admin.form.editUser', [
            'userID' => $id,
            'roles'  => $roleArr,
            'role'   => $userRole,
            'user'   => $userData
        ]);
    }

    //  Submit the updated user
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
        Log::info('User ID-'.$id.' has updated their information.');
        return redirect(route('admin.users.index'))->with('success', 'User Updated Successfully');
    }

    //  Deactivae an active user
    public function destroy($id)
    {
        //  Delete any file links that the user may have
        $linkController = new FileLinksController();
        $links = $linkController->show($id);
        
        $links = FileLinks::where('user_id', $id)->get();
        
        
        foreach($links as $link)
        {
            $linkController->destroy($link->link_id);
//            Log::notice($link);
        }
        
         //  Update the user data
        User::find($id)->update(
        [
            'active'   => 0
        ]);
        Log::notice('User ID-'.$id.' has been deactivated by User -'.Auth::user()->user_id);
    }
}

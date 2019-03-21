<?php

namespace App\Http\Controllers\Admin;

use DB;
use Mail;
use App\Role;
use App\User;
use Carbon\Carbon;
use App\UserInitialize;
use App\Mail\InitializeUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

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
        return view('admin.userIndex');
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
        Mail::to($request->email)->send(new InitializeUser($hash, $request->username, $request->first_name.' '.$request->last_name));
        
        Log::info('New User ID-'.$userID.' Created by ID-'.Auth::user()->user_id);
        
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
            return abort(404);
        }
        
        return view('account.initializeUser', ['hash' => $hash]);
    }
    
    //  Submit the initialize user form
    public function submitInitializeUser(Request $request, $hash)
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

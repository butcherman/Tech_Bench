<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use DB;
use App\Role;
use App\User;
use App\UserLogins;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
        $request->validate([
            'username'   => 'required|unique:users',
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|unique:users',
            'password'   => 'required|string|min:6|confirmed'
        ]);
        
        //  Create the user
        $newUser = User::create([
            'username'   => $request->username,
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => bcrypt($request->password),
            'active'     => 1
        ]);
        
        $userID = $newUser->user_id;
        
        //  Assign the users role
        DB::insert('INSERT INTO `user_role` (`user_id`, `role_id`) VALUES (?, ?)', [$userID, $request->role]);
        
        return redirect(route('admin.users.index'))->with('success', 'User Created Successfully');
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
        
         //  Update the user data
        User::find($id)->update(
        [
            'password'   => bcrypt($request->password)
        ]);

        
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
        
        return redirect(route('admin.users.index'))->with('success', 'User Updated Successfully');
    }

    //  Deactivae an active user
    public function destroy($id)
    {
         //  Update the user data
        User::find($id)->update(
        [
            'active'   => 0
        ]);
    }
}

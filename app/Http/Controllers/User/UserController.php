<?php

namespace App\Http\Controllers\User;

use App\Actions\User\GetUserRoles;
use App\Events\NewUserCreated;
use Inertia\Inertia;

use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Models\UserEmailNotifications;
use App\Models\UserRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     *  User Settings Page
     */
    public function index()
    {
        $this->authorize('view', Auth::user());

        return Inertia::render('User/settings', [
            'user'   => Auth::user()->makeVisible('user_id'),
            'notify' => UserEmailNotifications::find(Auth::user()->user_id),
        ]);
    }

    /**
     *  Administration page for creating new users
     */
    public function create()
    {
        $this->authorize('create', Auth::user());

        //  A user cannot add a user with higher permissions than themselves
        $roles = (new GetUserRoles)->run();

        return Inertia::render('User/create', [
            'roles' => $roles,
        ]);
    }

    /**
     *  Submit the new user form
     */
    public function store(UserRequest $request)
    {
        $user = $request->toArray();
        //  Generate a random password for the user
        $user['password'] = Hash::make(strtolower(Str::random(15)));

        $newUser = User::create($user);
        event(new NewUserCreated($newUser));

        return back()->with(['message' => 'New User Created Successfully', 'type' => 'success']);
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
     *  Edit a users information
     */
    public function edit($id)
    {
        $this->authorize('update', Auth::user());

        //  A user cannot add a update with higher permissions than themselves
        $userDetails = User::where('username', $id)->first()->makeVisible(['role_id', 'user_id']);
        if(Auth::user()->role_id > $userDetails->role_id)
        {
            return back()->with(['message' => 'You cannot modify a user with higher permissions than you', 'type' => 'danger']);
        }

        $roles = (new GetUserRoles)->run();

        return Inertia::render('User/create', [
            'roles'        => $roles,
            'user_details' => $userDetails,
        ]);
    }

    /**
     *  Update a users account settings
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->toArray());

        return back()->with(['message' => 'Account Settings Updated', 'type' => 'success']);
    }

    /**
     *  Disable a user
     */
    public function destroy($id)
    {
        $user = User::where('username', $id)->first();

        $msg = 'Successfully Deactivated '.$user->full_name;
        Log::stack(['user', 'auth'])->notice('User '.$user->username.' has been deactivated by '.Auth::user()->username);

        $user->delete();
        return back()->with(['message' => $msg, 'type' => 'success']);
    }
}

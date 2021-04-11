<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;

use App\Models\User;
use App\Events\NewUserCreated;
use App\Actions\User\GetUserRoles;
use App\Http\Controllers\Controller;
use App\Models\UserEmailNotifications;
use App\Http\Requests\User\UserRequest;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     *  User Settings Page
     */
    public function index()
    {
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
        $roles = (new GetUserRoles)->run(Auth::user());

        return Inertia::render('User/create', [
            'roles' => $roles,
        ]);
    }

    /**
     *  Submit the new user form
     */
    public function store(UserRequest $request)
    {
        $this->authorize('create', Auth::user());
        $user = $request->toArray();
        //  Generate a random password for the user
        $user['password'] = Hash::make(strtolower(Str::random(15)));

        $newUser = User::create($user);
        Log::notice('New user '.$newUser->full_name.' has been created by '.Auth::user()->full_name);
        event(new NewUserCreated($newUser));

        return back()->with(['message' => 'New User Created Successfully', 'type' => 'success']);
    }

    /**
     *  Edit a users information
     */
    public function edit($id)
    {
        $userDetails = User::where('username', $id)->firstOrFail()->makeVisible(['role_id', 'user_id']);

        //  A user cannot add a update with higher permissions than themselves
        $this->authorize('update', $userDetails);
        if(Auth::user()->role_id > $userDetails->role_id)
        {
            return back()->with(['message' => 'You cannot modify a user with higher permissions than you', 'type' => 'danger']);
        }

        $roles = (new GetUserRoles)->run(Auth::user());

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

        $this->authorize('update', $user);
        if(Auth::user()->role_id > $user->role_id)
        {
            return back()->with(['message' => 'You cannot modify a user with higher permissions than you', 'type' => 'danger']);
        }

        $user->update($request->toArray());

        Log::info('User information for User ID '.$id.' Name - '.$user->username.' has been updated by '.Auth::user()->full_name);
        return back()->with(['message' => 'Account Settings Updated', 'type' => 'success']);
    }

    /**
     *  Disable a user
     */
    public function destroy($id)
    {
        $user = User::where('username', $id)->first();
        $this->authorize('delete', $user);

        $msg = 'Successfully Deactivated '.$user->full_name;
        Log::notice('User '.$user->username.' has been deactivated by '.Auth::user()->username);

        $user->delete();
        return back()->with(['message' => $msg, 'type' => 'success']);
    }
}

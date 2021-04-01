<?php

namespace App\Http\Controllers\User;

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

        return Inertia::render('User/create', [
            'roles' => UserRoles::all(),
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
     *  Update a users account settings
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->toArray());

        return back()->with(['message' => 'Account Settings Updated', 'type' => 'success']);
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

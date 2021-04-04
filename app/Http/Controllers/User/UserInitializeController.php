<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\InitializeUserRequest;
use App\Http\Requests\User\PasswordRequest;
use App\Models\User;
use App\Models\UserInitialize;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserInitializeController extends Controller
{
    /**
     *  Show the finish setup form
     */
    public function show($id)
    {
        $link = UserInitialize::where('token', $id)->first();

        if(!$link)
        {
            abort(404, 'The Setup Link you are looking for cannot be found');
        }

        return Inertia::render('User/initialize', [
            'token' => $id,
            'name'  => $link->username,
        ]);
    }

    /**
     *  Finish setting up the new user
     */
    public function update(InitializeUserRequest $request, $id)
    {
        //  Validate the user
        $link = UserInitialize::where('token', $id)->firstOrFail();

        $user = User::where('email', $request->email)->first();

        if($link->username !== $user->username)
        {
            abort(403);
        }

        //  Determine the new expiration date
        $expires = config('auth.passwords.settings.expire') ? Carbon::now()->addDays(config('auth.passwords.settings.expire')) : null;

        $user->forceFill(['password' => Hash::make($request->password), 'password_expires' => $expires]);
        $user->save();

        event(new PasswordReset($user));
        $link->delete();
        Auth::login($user);

        return redirect(route('dashboard'))->with(['message' => 'Your account is setup', 'type' => 'success']);
    }
}

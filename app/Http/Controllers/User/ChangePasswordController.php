<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Events\UserPasswordChanged;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\ChangeUserPasswordRequest;

class ChangePasswordController extends Controller
{
    /**
     * Page for a user to change their own password
     */
    public function index()
    {
        return Inertia::render('User/ChangePassword');
    }

    /**
     * Store a reset password for the logged in user
     */
    public function store(ChangePasswordRequest $request)
    {
        $user    = User::find($request->user()->user_id);
        $expires = config('auth.passwords.settings.expire') ? Carbon::now()->addDays(config('auth.passwords.settings.expire')) : null;

        $user->forceFill(['password' => Hash::make($request->password), 'password_expires' => $expires]);
        $user->save();

        event(new UserPasswordChanged($user));
        return redirect(route('dashboard'))->with('success', __('user.password_changed'));
    }

    /**
     * Update the password for a specific user
     */
    public function update(ChangeUserPasswordRequest $request, $id)
    {
        $expires = config('auth.passwords.settings.expire') ? Carbon::now()->addDays(config('auth.passwords.settings.expire')) : null;
        $user    = User::where('username', $id)->firstOrFail();

        $user->forceFill(['password' => Hash::make($request->password), 'password_expires' => $expires]);
        $user->save();

        event(new UserPasswordChanged($user));
        return back()->with('success', __('user.password_updated'));
    }
}

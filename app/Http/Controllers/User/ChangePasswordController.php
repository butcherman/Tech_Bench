<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
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
        $expires = $user->getNewExpireTime();

        $user->forceFill(['password' => Hash::make($request->password), 'password_expires' => $expires]);
        $user->save();

        Log::channel('user')->info('User '.$request->user()->username.' has updated their password');
        return redirect(route('dashboard'))->with('success', __('user.password_changed'));
    }

    /**
     * Update the password for a specific user
     */
    public function update(ChangeUserPasswordRequest $request, $id)
    {
        $user    = User::where('username', $id)->firstOrFail();
        $expires = $user->getNewExpireTime();

        $user->forceFill(['password' => Hash::make($request->password), 'password_expires' => $expires]);
        $user->save();

        Log::channel('user')->info('Password for User '.$request->user()->username.' has been updated by '.Auth::user()->username);
        return back()->with('success', __('user.password_updated'));
    }
}

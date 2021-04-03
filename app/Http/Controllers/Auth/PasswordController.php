<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Inertia\Inertia;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\PasswordRequest;
use App\Http\Requests\Auth\ResetLinkRequest;
use App\Http\Requests\Auth\ResetTokenRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class PasswordController extends Controller
{
    /**
     * Forgot Password Page
     */
    public function index()
    {
        return Inertia::render('Auth/forgotPassword');
    }

    /**
     *  Submit Forgot Password page
     */
    public function store(ResetLinkRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));
        return $status === Password::RESET_LINK_SENT ? back()->with(['message' => $status, 'type' => 'success']) : back()->withErrors(['email' => $status]);
    }

    /**
     *  Show the change password form
     */
    public function edit()
    {
        return Inertia::render('User/changePassword');
    }

    /**
     *  Update the users password
     */
    public function update(PasswordRequest $request, $id)
    {
        $user = User::where('username', $id)->first();

        //  Verify that the user is actually using a new password
        if(Hash::check($request->password, $user->password))
        {
            return back()->with(['message' => 'You cannot use the same password', 'type' => 'danger']);
        }

        //  Determine the new expiration date
        $expires = config('auth.passwords.settings.expire') ? Carbon::now()->addDays(config('auth.passwords.settings.expire')) : null;

        $user->forceFill(['password' => Hash::make($request->password), 'password_expires' => $expires]);
        $user->save();

        event(new PasswordReset($user));
        return back()->with(['message' => 'Password Successfully Updated', 'type' => 'success']);
    }

    /*
    *   Reset password page for users who have successfully submitted a reset request
    */
    public function resetPassword(Request $request)
    {
        return Inertia::render('Auth/resetPassword', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }

    /*
    *   Submit the reset password page
    */
    public function submitReset(ResetTokenRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function($user, $password) use ($request)
            {
                //  Determine the new expiration date
                $expires = config('auth.passwords.settings.expire') ? Carbon::now()->addDays(config('auth.passwords.settings.expire')) : null;

                $user->forceFill([
                    'password' => Hash::make($password),
                    'password_expires' => $expires,
                ]);

                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
                ? redirect()->route('login.index')->with(['message' => 'Password Successfully Updated', 'type' => 'success'])
                : back()->withErrors(['email' => [__($status)]]);

    }
}

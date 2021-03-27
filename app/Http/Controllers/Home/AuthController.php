<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetLinkRequest;
use App\Http\Requests\Auth\ResetTokenRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;


class AuthController extends Controller
{
    /*
    *   Post request to log user in
    */
    public function login(LoginRequest $request)
    {
        $user = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        //  Successful authentication re-routes to the dashboard, or the page that the user tried to visit
        if(Auth::attempt($user, $request->remember))
        {
            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['username' => 'Your username or password does not match our records']);
    }

    /*
    *   Forgot Password Form
    */
    public function forgotPassword()
    {
        return Inertia::render('Auth/forgotPassword');
    }

    /*
    *   Submit Forgot Password Form
    */
    public function getResetLink(ResetLinkRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT ? back()->with(['message' => $status, 'type' => 'success']) : back()->withErrors(['email' => $status]);
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
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
                ? redirect()->route('login')->with(['message' => 'Password Successfully Updated', 'type' => 'success'])
                : back()->withErrors(['email' => [__($status)]]);

    }
}

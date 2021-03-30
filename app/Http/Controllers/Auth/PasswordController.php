<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetLinkRequest;
use App\Http\Requests\Auth\ResetTokenRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        //
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
                ? redirect()->route('login.index')->with(['message' => 'Password Successfully Updated', 'type' => 'success'])
                : back()->withErrors(['email' => [__($status)]]);

    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetTokenRequest;
use App\Listeners\LogPasswordReset;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class ResetPasswordSubmitController extends Controller
{
    /**
     *  Submit the Reset Password via token request
     */
    public function __invoke(ResetTokenRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
                function($user, $password)
                {
                    //  Determine the new expiration date
                    $expires = config('auth.passwords.settings.expire') ? Carbon::now()->addDays(config('auth.passwords.settings.expire')) : null;

                    $user->forceFill([
                        'password'         => Hash::make($password),
                        'password_expires' => $expires,
                    ]);

                    $user->save();
                    event(new PasswordReset($user));
                }
            );

        return $status == Password::PASSWORD_RESET
                ? redirect()->route('login.index')->with([
                    'message' => 'Password Successfully Updated',
                    'type'    => 'success'
                ]) : back()->withErrors(['email' => [__($status)]]);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetTokenRequest;

class ResetPasswordSubmitController extends Controller
{
    public function __construct()
    {
        //  To help prevent bots, we will not allow more than 50 Reset attempts within a two hour period
        $this->middleware('throttle:50,120');
    }

    /**
     *  Submit the Reset Password via token request
     */
    public function __invoke(ResetTokenRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'), function($user, $password) {
                //  Determine the new expiration date
                $expires = $user->getNewExpireTime();

                $user->forceFill([
                    'password'         => Hash::make($password),
                    'password_expires' => $expires,
                ]);

                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET ?
                        redirect()->route('login.index')->with('success', 'Password Successfully Updated') :
                        back()->withErrors(['email' => [__($status)]]);
    }
}

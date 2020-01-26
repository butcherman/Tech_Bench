<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';
    protected $email;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->email = 'email@em.com';
    }

    protected function resetPassword($user, $password)
    {
        Log::info('User ID - '.$user->user_id.' '.$user->first_name.' '.$user->last_name.' has updated their password.');

        $this->setUserPassword($user, $password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        event(new PasswordReset($user));
        $this->guard()->login($user);
    }
}

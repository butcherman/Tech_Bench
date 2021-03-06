<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $maxAttempts, $decayMinutes;

    //  Where to redirect users after login
    protected $redirectTo = '/dashboard';

    //  Create a new controller instance
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->maxAttempts  = 5;
        $this->decayMinutes = 10;
    }

    //  Override username function to use the username instead of email
    public function username()
    {
        return 'username';
    }
}

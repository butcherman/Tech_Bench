<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    //  Where to redirect users after login
    protected $redirectTo = '/dashboard';

    //  Create a new controller instance
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    //  Override username function to use the username instead of email
    public function username()
    {
        return 'username';
    }
    
    //  Override the Credentials function to include the "Active" field
    protected function credentials(Request $request)
    {
        return [
            'username' => $request->{$this->username()},
            'password' => $request->password,
            'active'   => '1',
        ];
    }
}

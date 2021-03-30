<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     *  Attempt to login a user
     */
    public function __invoke(LoginRequest $request)
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
}

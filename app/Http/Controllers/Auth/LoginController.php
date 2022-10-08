<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    public function __construct()
    {
        //  To help prevent bots, we will not allow more than 50 login attempts within a two hour period
        $this->middleware('throttle:50,120');
    }

    /**
     *  Attempt to log a user in
     */
    public function __invoke(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}

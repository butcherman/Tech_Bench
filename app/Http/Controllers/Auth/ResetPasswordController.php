<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResetPasswordController extends Controller
{
    public function __construct()
    {
        //  To help prevent bots, we will not allow more than 50 login attempts within a two hour period
        $this->middleware('throttle:50,120');
    }

    /**
     *  For users with reset token to allow user to reset their password
     */
    public function __invoke(Request $request)
    {
        // If the user is trying to visit the page without a token or email address, show 404 error
        if(empty($request->token) || empty($request->email))
        {
            abort(404);
        }

        return Inertia::render('Auth/ResetPassword', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }
}

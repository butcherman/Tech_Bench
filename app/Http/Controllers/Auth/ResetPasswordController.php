<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class ResetPasswordController extends Controller
{
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

        return Inertia::render('Auth/password/reset', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }
}

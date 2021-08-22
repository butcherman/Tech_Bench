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
        return Inertia::render('Auth/password/reset', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }
}

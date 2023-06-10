<?php

namespace App\Http\Controllers\Auth;

use App\Actions\BuildPasswordRules;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResetPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //  If the user is trying to visit the page without a proper token or email, show 404
        if (! $request->has('token') || ! $request->has('email')) {
            abort(404);
        }

        return Inertia::render('Auth/ResetPassword', [
            'token' => $request->token,
            'email' => $request->email,
            'password-rules' => (new BuildPasswordRules)->build(),
        ]);
    }
}

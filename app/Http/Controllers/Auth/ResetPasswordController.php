<?php

namespace App\Http\Controllers\Auth;

use App\Actions\BuildPasswordRules;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

/**
 * Reset password form
 */
class ResetPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        //  If the user is trying to visit the page without a proper token or email, show 404
        if (! $request->has('token') || ! $request->has('email')) {
            // TODO - should this trigger custom exception?
            abort(404);
        }

        return Inertia::render('Auth/ResetPassword', [
            'token' => $request->token,
            'email' => $request->email,
            'password-rules' => Cache::get('passwordRules', (new BuildPasswordRules)->build()),
        ]);
    }
}

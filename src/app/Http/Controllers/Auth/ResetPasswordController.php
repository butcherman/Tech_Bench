<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Auth\InvalidResetPasswordTokenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Service\Cache;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResetPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ResetPasswordRequest $request)
    {
        //  If the user is trying to visit the page without a proper token or email, show 404
        if (! $request->has('token') || ! $request->has('email')) {
            throw (new InvalidResetPasswordTokenException($request));
        }

        return Inertia::render('Auth/ResetPassword', [
            'token' => $request->token,
            'email' => $request->email,
            'rules' => Cache::PasswordRules(),
        ]);

    }
}

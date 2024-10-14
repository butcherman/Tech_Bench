<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Auth\InvalidResetPasswordTokenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Service\Cache;
use Inertia\Inertia;
use Inertia\Response;

class ResetPasswordController extends Controller
{
    /**
     * Submit the Reset Password Form
     */
    public function __invoke(ResetPasswordRequest $request): Response
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

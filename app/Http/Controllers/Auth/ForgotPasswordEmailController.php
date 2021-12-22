<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;

use App\Http\Controllers\Controller;

class ForgotPasswordEmailController extends Controller
{
    /**
     *  Display page to allow user to reset their password
     */
    public function __invoke()
    {
        return Inertia::render('Auth/password/forgot');
    }
}

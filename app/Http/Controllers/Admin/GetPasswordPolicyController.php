<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GetPasswordPolicyController extends Controller
{
    /**
     * Show the Password Policy Form
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Admin/PasswordPolicy', [
            'password_expires' => config('auth.passwords.settings.expire'),
        ]);
    }
}

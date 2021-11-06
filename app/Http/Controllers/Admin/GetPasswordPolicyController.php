<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GetPasswordPolicyController extends Controller
{
    /**
     * Show the Password Policy Form
     */
    public function __invoke(Request $request)
    {
        $this->authorize('manage', User::class);

        return Inertia::render('Admin/PasswordPolicy', [
            'password_expires' => config('auth.passwords.settings.expire'),
        ]);
    }
}

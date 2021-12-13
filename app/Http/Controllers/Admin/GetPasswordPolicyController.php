<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;

use App\Models\User;
use App\Http\Controllers\Controller;

class GetPasswordPolicyController extends Controller
{
    /**
     * Show the Password Policy Form
     */
    public function __invoke()
    {
        $this->authorize('manage', User::class);

        return Inertia::render('Admin/PasswordPolicy', [
            'password_expires' => config('auth.passwords.settings.expire'),
        ]);
    }
}

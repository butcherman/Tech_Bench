<?php

namespace App\Http\Controllers\User;

use App\Actions\BuildPasswordRules;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserInitialize;
use Inertia\Inertia;

class InitializeUserController extends Controller
{
    /**
     * Allow User to finish setting up their account
     */
    public function __invoke(UserInitialize $initLink)
    {
        return Inertia::render('User/Initialize', [
            'link' => $initLink,
            'user' => User::where('username', $initLink->username)->first(),
            'password_rules' => (new BuildPasswordRules)->build(),
        ]);
    }
}

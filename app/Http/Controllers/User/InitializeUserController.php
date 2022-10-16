<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserInitialize;

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
        ]);
    }
}

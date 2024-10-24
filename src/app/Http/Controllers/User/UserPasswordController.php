<?php

// TODO - Refactor

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Service\Cache;
use Inertia\Inertia;

class UserPasswordController extends Controller
{
    /**
     * Display the resource.
     */
    public function __invoke()
    {
        return Inertia::render('User/Password', [
            'rules' => Cache::PasswordRules(),
        ]);
    }
}

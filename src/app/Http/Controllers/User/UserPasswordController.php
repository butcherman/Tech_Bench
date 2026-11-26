<?php

namespace App\Http\Controllers\User;

use App\Facades\CacheData;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class UserPasswordController extends Controller
{
    /**
     * Show the Password Change Page.
     */
    public function __invoke(): Response
    {
        return Inertia::render('User/Password', [
            'rules' => fn () => CacheData::passwordRules(),
        ]);
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Actions\BuildPasswordRules;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class UserPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('User/Password', [
            'rules' => Cache::get('passwordRules', (new BuildPasswordRules)->build()),
        ]);
    }
}
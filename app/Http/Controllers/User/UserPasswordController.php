<?php

namespace App\Http\Controllers\User;

use App\Actions\BuildCacheData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

/**
 * Allow a user to change their own password form
 */
class UserPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        return Inertia::render('User/Password', [
            'rules' => Cache::get('passwordRules', BuildCacheData::buildPasswordRules()),
        ]);
    }
}

<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserInitialize;
use App\Http\Controllers\Controller;

class InitializeUserController extends Controller
{
    /**
     * Allow a user to finish setting up their profile
     */
    public function __invoke(Request $request)
    {
        $link = UserInitialize::where('token', $request->token)->firstOrFail();

        return Inertia::render('User/Initialize', [
            'link' => $link,
            'name' => User::where('username', $link->username)->first()->full_name,
        ]);
    }
}

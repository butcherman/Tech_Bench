<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;

use App\Models\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ListActiveUsersController extends Controller
{
    /**
     *  List all active users
     */
    public function __invoke(Request $request)
    {
        $this->authorize('create', User::class);

        $userList = User::with('UserRoles')->get()->makeHidden(['first_name', 'last_name', 'initials']);
        return Inertia::render('User/list', ['users' => $userList]);
    }
}

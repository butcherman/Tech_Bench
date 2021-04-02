<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ListActiveUsersController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $userList = User::with('UserRoles')->get()->makeHidden(['first_name', 'last_name', 'initials']);

        return Inertia::render('User/list', ['users' => $userList]);
    }
}

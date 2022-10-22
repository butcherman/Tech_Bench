<?php

namespace App\Http\Controllers\Admin\User;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserDisabledController extends Controller
{
    /**
     * Show the listing of disabled users
     */
    public function __invoke()
    {
        $this->authorize('manage', User::class);

        return Inertia::render('Admin/User/DisabledUsers', [
            'userList' => User::onlyTrashed()->get()->makeVisible(['user_id', 'deleted_at']),
        ]);
    }
}

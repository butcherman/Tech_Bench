<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;

use App\Models\User;
use App\Http\Controllers\Controller;

class DeactivatedUserController extends Controller
{
    /**
     * Show all deactivated users
     */
    public function __invoke()
    {
        $this->authorize('create', User::class);

        return Inertia::render('Admin/User/DeactivatedUsers', [
            'user_list' => User::onlyTrashed()->get(),
        ]);
    }
}

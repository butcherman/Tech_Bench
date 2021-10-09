<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeactivatedUserController extends Controller
{
    /**
     * Show all deactivated users
     */
    public function __invoke(Request $request)
    {
        $this->authorize('create', User::class);

        return Inertia::render('Admin/User/DeactivatedUsers', [
            'user_list' => User::onlyTrashed()->get(),
        ]);
    }
}

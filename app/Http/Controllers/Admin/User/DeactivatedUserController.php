<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * Deactivate a user so they can no longer login
 * Note:  Users are not deleted to keep their contributions to app in tact
 */
class DeactivatedUserController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->authorize('manage', User::class);

        return Inertia::render('Admin/User/Index', [
            'userList' => User::onlyTrashed()->with('UserRole')->get(),
        ]);
    }
}

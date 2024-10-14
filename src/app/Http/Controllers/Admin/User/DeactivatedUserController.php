<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DeactivatedUserController extends Controller
{
    /**
     * Show a list of deactivated users.
     */
    public function __invoke(): Response
    {
        $this->authorize('viewAny', User::class);

        return Inertia::render('Admin/User/Deactivated', [
            'user-list' => User::onlyTrashed()
                ->with('UserRole')
                ->get()
                ->makeVisible('deleted_at'),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\UserAdministrationService;
use Inertia\Inertia;
use Inertia\Response;

class DisabledUserController extends Controller
{
    /**
     * Show a list of users that have been deactivated
     */
    public function __invoke(UserAdministrationService $svc): Response
    {
        $this->authorize('manage', User::class);

        return Inertia::render('Admin/User/Deactivated', [
            'user-list' => Inertia::defer(fn () => $svc->getAllUsers(true)),
        ]);
    }
}

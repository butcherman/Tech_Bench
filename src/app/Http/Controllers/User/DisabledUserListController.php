<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\UserAdministrationService;
use Inertia\Inertia;
use Inertia\Response;

class DisabledUserListController extends Controller
{
    /**
     * Show a list of users that have been deactivated
     */
    public function __invoke(UserAdministrationService $svc): Response
    {
        $this->authorize('viewAny', User::class);

        return Inertia::render('User/Admin/Disabled', [
            'user-list' => Inertia::defer(fn () => $svc->getAllUsers(true)),
        ]);
    }
}

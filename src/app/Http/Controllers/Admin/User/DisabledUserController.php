<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\UserAdministrationService;
use Inertia\Inertia;
use Inertia\Response;

class DisabledUserController extends Controller
{
    public function __construct(protected UserAdministrationService $svc) {}

    /**
     * Show a list of users that have been deactivated
     */
    public function __invoke(): Response
    {
        $this->authorize('manage', User::class);

        return Inertia::render('Admin/User/Deactivated', [
            'user-list' => fn () => $this->svc->getAllUsers(true),
        ]);
    }
}

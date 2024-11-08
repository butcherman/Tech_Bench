<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserAdministrationRequest;
use App\Models\User;
use App\Services\User\UserAdministrationService;
use App\Traits\UserRoleTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserAdministrationController extends Controller
{
    use UserRoleTrait;

    public function __construct(protected UserAdministrationService $svc) {}

    /**
     * Display a listing of all active Users.
     */
    public function index(): Response
    {
        $this->authorize('manage', User::class);

        return Inertia::render('Admin/User/Index', [
            'user-list' => fn () => $this->svc->getAllUsers(),
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(Request $request): Response
    {
        $this->authorize('create', User::class);

        return Inertia::render('Admin/User/Create', [
            'roles' => fn () => $this->getAvailableRoles($request->user()),
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(UserAdministrationRequest $request): RedirectResponse
    {
        $user = $this->svc->createUser($request->safe()->collect());

        return redirect(route('admin.user.show', $user))
            ->with(
                'success',
                __('admin.user.created', ['user' => $user->full_name])
            );
    }

    /**
     * Display a users information.
     */
    public function show(User $user): Response
    {
        $this->authorize('manage', $user);

        return Inertia::render('Admin/User/Show', [
            'user' => fn () => $user->getAdminLoad(),
            'role' => fn () => $user->UserRole,
            'last-login' => fn () => $user->getLastLogin(),
            'thirty-day-count' => fn () => $user->getLoginHistory(30)->count(),
        ]);
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(Request $request, User $user): Response
    {
        $this->authorize('update', $user);

        return Inertia::render('Admin/User/Edit', [
            'roles' => fn () => $this->getAvailableRoles($request->user()),
            'user' => fn () => $user->getAdminLoad(),
        ]);
    }

    /**
     * Update a users information.
     */
    public function update(UserAdministrationRequest $request, User $user): RedirectResponse
    {
        $this->svc->updateUser($request->safe()->collect(), $user);

        return redirect(route('admin.user.show', $user->username))
            ->with('success', __('admin.user.updated', ['user' => $user->full_name]));
    }

    /**
     * Soft Delete/Disable a user.
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $this->svc->destroyUser($user);

        return redirect(route('admin.user.index'))
            ->with(
                'warning',
                __('admin.user.disabled', ['user' => $user->full_name])
            );
    }

    /**
     * Restore a disabled user
     */
    public function restore(User $user): RedirectResponse
    {
        $this->authorize('manage', $user);

        $this->svc->restoreUser($user);

        return back()->with(
            'success',
            __('admin.user.restored', ['user' => $user->full_name])
        );
    }
}

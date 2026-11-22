<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Admin\UserAdministrationRequest;
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
     * Display a listing of all active users.
     */
    public function index(): Response
    {
        $this->authorize('viewAny', User::class);

        return Inertia::render('User/Admin/Index', [
            'user-list' => Inertia::defer(
                fn () => $this->svc->getAllUsers(),
                rescue: true
            ),
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('User/Admin/Create', [
            'roles' => fn () => $this->getAvailableRoles($request->user()),
        ]);
    }

    /**
     * Store a newly created user in storage.
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
     * Display the users profile.
     */
    public function show(User $user): Response
    {
        $this->authorize('view', $user);

        return Inertia::render('User/Admin/Show', [
            'user' => fn () => $user->getAdminLoad(),
            'last-login' => fn () => $user->getLastLogin(),
            'thirty-day-count' => fn () => $user->getLoginHistory(30)->count(),
            'allow-two-fa' => fn () => config('auth.twoFa.enabled'),
            'allow-save-device' => fn () => config('auth.twoFa.allow_save_device'),
            'saved-devices-count' => fn () => $user->DeviceTokens->count(),
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(string $id)
    {
        //
        return 'edit';
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    /**
     * Soft Delete/Disable a user.
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $this->svc->disableUser($user);

        return redirect(route('admin.user.index'))
            ->with(
                'warning',
                __('admin.user.disabled', ['user' => $user->full_name])
            );
    }
}

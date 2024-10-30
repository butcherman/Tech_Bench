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
        $this->authorize('viewAny', User::class);

        return Inertia::render('Admin/User/Index', [
            'user-list' => $this->svc->getAllUsers(),
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(Request $request): Response
    {
        $this->authorize('create', User::class);

        return Inertia::render('Admin/User/Create', [
            'roles' => $this->getAvailableRoles($request->user()),
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
        $this->authorize('view', $user);

        return Inertia::render('Admin/User/Show', [
            'user' => [],
            'role' => [],
            'last-login' => [],
            'thirty-day-count' => 0,
        ]);
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update a users information.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Soft Delete/Disable a user.
     */
    public function destroy(string $id)
    {
        //
    }
}

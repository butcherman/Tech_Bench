<?php

namespace App\Http\Controllers\Admin\User;

use App\Actions\AvailableUserRoles;
use App\Events\Feature\FeatureChangedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserAdministrationRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserAdministrationController extends Controller
{
    public function __construct(protected AvailableUserRoles $roles) {}

    /**
     * Show a list of active users.
     */
    public function index(): Response
    {
        $this->authorize('viewAny', User::class);

        return Inertia::render('Admin/User/Index', [
            'user-list' => User::with('UserRole')->get(),
        ]);
    }

    /**
     * Show form to create a new user.
     */
    public function create(Request $request): Response
    {
        $this->authorize('create', User::class);

        return Inertia::render('Admin/User/Create', [
            'roles' => $this->roles->get($request->user()),
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(UserAdministrationRequest $request): RedirectResponse
    {
        $newUser = User::create($request->toArray());

        return redirect(route('admin.user.show', $newUser->username))
            ->with('success', __('admin.user.created', [
                'user' => $newUser->full_name,
            ]));
    }

    /**
     * Show data for the selected User.
     */
    public function show(User $user): Response
    {
        $this->authorize('view', $user);

        // How many days back do we show for login history?
        $loginHistoryDays = 1825;

        return Inertia::render('Admin/User/Show', [
            'user' => $user->makeVisible(['created_at', 'updated_at', 'deleted_at']),
            'role' => $user->UserRole,
            'last-login' => $user->getLoginHistory($loginHistoryDays)->last(),
            'thirty-day-count' => $user->getLoginHistory(30)->count(),
        ]);
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(Request $request, User $user): Response
    {
        $this->authorize('update', $user);

        return Inertia::render('Admin/User/Edit', [
            'roles' => $this->roles->get($request->user()),
            'user' => $user->makeVisible('role_id'),
        ]);
    }

    /**
     * Update an active User.
     */
    public function update(UserAdministrationRequest $request, User $user): RedirectResponse
    {
        $user->update($request->toArray());

        // We fire the Feature Changed event to rebuild user Feature permissions
        event(new FeatureChangedEvent);

        return redirect(route('admin.user.show', $user->username))
            ->with('success', __('admin.user.updated', [
                'user' => $user->full_name,
            ]));
    }

    /**
     * Soft Delete a User
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect(route('admin.user.index'))
            ->with('warning', __('admin.user.disabled', [
                'user' => $user->full_name,
            ]));
    }

    /**
     * Restore a Disabled User
     */
    public function restore(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->restore();

        return back()->with('success', __('admin.user.restored', [
            'user' => $user->full_name,
        ]));
    }
}

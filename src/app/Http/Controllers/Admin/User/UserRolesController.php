<?php

namespace App\Http\Controllers\Admin\User;

use App\Actions\AvailableUserRoles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserRoleRequest;
use App\Models\UserRole;
use App\Service\Admin\UserRoleAdministrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserRolesController extends Controller
{
    public function __construct(
        protected AvailableUserRoles $roles,
        protected UserRoleAdministrationService $svc
    ) {}

    /**
     * Display a listing of available User Roles
     */
    public function index(Request $request): Response
    {
        $this->authorize('view', UserRole::class);

        return Inertia::render('Admin/Role/Index', [
            'roles' => UserRole::all(),
        ]);
    }

    /**
     * Show the form for creating a new role or copying an existing one.
     */
    public function create(Request $request): Response
    {
        $this->authorize('create', UserRole::class);

        // If we are copying an existing role, fetch that role
        $baseRole = $request->role_id
            ? UserRole::find($request->role_id)
            : null;

        return Inertia::render('Admin/Role/Create', [
            'base-role' => $baseRole,
            'permission-list' => $this->svc->getRolePermissionTypes(),
            'permission-values' => $baseRole
                ? $baseRole->UserRolePermission
                : [],
        ]);
    }

    /**
     * Store a newly created User Role.
     */
    public function store(UserRoleRequest $request): RedirectResponse
    {
        $newRole = $this->svc->createNewRole($request);

        return redirect(route('admin.user-roles.show', $newRole->role_id))
            ->with('success', __('admin.user-role.created'));
    }

    /**
     * Display a User Role along with its permissions
     */
    public function show(UserRole $user_role): Response
    {
        $this->authorize('view', $user_role);

        return Inertia::render('Admin/Role/Show', [
            'role' => $user_role->makeVisible(['allow_edit']),
            'permission-list' => $this->svc->getRolePermissionTypes(),
            'permission-values' => $user_role->UserRolePermission,
        ]);
    }

    /**
     * Show the form for editing an existing User Role
     */
    public function edit(UserRole $user_role): Response
    {
        $this->authorize('update', $user_role);

        return Inertia::render('Admin/Role/Edit', [
            'base-role' => $user_role,
            'permission-list' => $this->svc->getRolePermissionTypes(),
            'permission-values' => $user_role->UserRolePermission,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRoleRequest $request, UserRole $user_role): RedirectResponse
    {
        $this->svc->updateExistingRole($request, $user_role);

        return back()->with('success', __('admin.user-role.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserRole $user_role): RedirectResponse
    {
        $this->authorize('delete', $user_role);

        $this->svc->destroyRole($user_role);

        return redirect(route('admin.user-roles.index'))
            ->with('warning', __('admin.user-role.destroyed'));
    }
}

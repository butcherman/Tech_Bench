<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserRoleRequest;
use App\Models\UserRole;
use App\Services\User\UserRoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserRolesController extends Controller
{
    public function __construct(protected UserRoleService $svc) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $this->authorize('viewAny', UserRole::class);

        return Inertia::render('Admin/Role/Index', [
            'roles' => fn () => $this->svc->getAllRoles(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        $this->authorize('create', UserRole::class);

        return Inertia::render('Admin/Role/Create', [
            'permission-list' => $this->svc->getRolePermissionTypes(),
            'base-role' => fn () => $request->role_id
                ? $this->svc->getRole($request->role_id)
                : null,
            'permission-values' => fn () => $request->role_id
                ? $this->svc->getRole($request->role_id)->UserRolePermission
                : [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRoleRequest $request): RedirectResponse
    {
        $newRole = $this->svc->createNewRole($request->safe()->collect());

        return redirect(route('admin.user-roles.show', $newRole->role_id))
            ->with('success', __('admin.user-role.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(UserRole $user_role): Response
    {
        $this->authorize('view', $user_role);

        return Inertia::render('Admin/Role/Show', [
            'role' => fn () => $user_role->makeVisible(['allow_edit']),
            'permission-list' => fn () => $this->svc->getRolePermissionTypes(),
            'permission-values' => fn () => $user_role->UserRolePermission,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserRole $user_role): Response
    {
        $this->authorize('update', $user_role);

        return Inertia::render('Admin/Role/Edit', [
            'base-role' => fn () => $user_role,
            'permission-list' => fn () => $this->svc->getRolePermissionTypes(),
            'permission-values' => fn () => $user_role->UserRolePermission,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRoleRequest $request, UserRole $user_role): RedirectResponse
    {
        $this->svc->updateExistingRole($request->safe()->collect(), $user_role);

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

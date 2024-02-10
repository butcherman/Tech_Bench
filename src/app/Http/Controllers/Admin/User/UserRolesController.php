<?php

namespace App\Http\Controllers\Admin\User;

use App\Actions\BuildUserRoles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserRoleRequest;
use App\Models\UserRole;
use App\Models\UserRolePermissionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('view', UserRole::class);

        return Inertia::render('Admin/Role/Index', [
            'roles' => BuildUserRoles::build($request->user()),
        ]);
    }

    /**
     * Show the form for creating a new role or copying an existing one.
     */
    public function create(Request $request)
    {
        $this->authorize('create', UserRole::class);

        return Inertia::render('Admin/Role/Create', [
            'base-role' => $request->role_id ?
                UserRole::find($request->role_id) : null,
            'permission-list' => $request->role_id ?
                UserRole::find($request->role_id)->UserRolePermission
                    ->groupBy('UserRolePermissionType.group') :
                UserRolePermissionType::all()->groupBy('group'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRoleRequest $request)
    {
        $newRole = $request->processNewRole();

        Log::info('New User Role created by '.
            $request->user()->username, $newRole->toArray());

        return redirect(route('admin.user-roles.show', $newRole->role_id))
            ->with('success', __('admin.user-role.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(UserRole $user_role)
    {
        $this->authorize('view', $user_role);

        return Inertia::render('Admin/Role/Show', [
            'role' => $user_role->makeVisible(['allow_edit']),
            'permission-list' => $user_role
                ->UserRolePermission
                ->groupBy('UserRolePermissionType.group'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserRoleRequest $request, UserRole $user_role)
    {
        $this->authorize('destroy', $user_role);

        $request->destroyRole();

        Log::stack(['daily', 'user'])
            ->notice('Role '.$user_role->name.' has been deleted by '.
                $request->user()->username);

        return redirect(route('admin.user-roles.index'))
            ->with('warning', __('admin.user-role.destroyed'));
    }
}

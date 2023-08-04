<?php

namespace App\Http\Controllers\Admin\User;

use App\Actions\GetAvailableUserRoles;
use App\Exceptions\RecordInUseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserRoleRequest;
use App\Models\UserRolePermissionTypes;
use App\Models\UserRoles;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

/**
 * User Role/User Permission Groups Administration
 */
class UserRoleController extends Controller
{
    /**
     * Display a listing of current Roles
     */
    public function index(Request $request)
    {
        $this->authorize('view', UserRoles::class);

        return Inertia::render('Admin/Role/Index', [
            'roles' => (new GetAvailableUserRoles)->build($request->user()),
        ]);
    }

    /**
     * Show the form for creating a new Role
     */
    public function create()
    {
        $this->authorize('create', UserRoles::class);

        return Inertia::render('Admin/Role/Create', [
            'permission-list' => UserRolePermissionTypes::all()->groupBy('group'),
        ]);
    }

    /**
     * Copy an existing Role to a new one
     */
    public function copy(UserRoles $user_role)
    {
        $this->authorize('create', UserRoles::class);

        $copyRole = $user_role;
        $copyRole->name = 'Copy of '.$user_role->name;
        $copyRole->description = 'Copy of '.$user_role->description;

        return Inertia::render('Admin/Role/Create', [
            'permission-list' => $user_role->UserRolePermissions->groupBy('UserRolePermissionTypes.group'),
            'base-role' => $copyRole,
        ]);
    }

    /**
     * Store a newly created Role
     */
    public function store(UserRoleRequest $request)
    {
        $newRole = $request->processNewRole();

        Log::notice('New User Role '.$request->name.' created by '.$request->user()->username, $request->only(['name', 'description']));

        return redirect(route('admin.user-roles.show', $newRole->role_id))->with('success', __('admin.user-roles.created'));
    }

    /**
     * Display the specified Role and its given permissions
     */
    public function show(UserRoles $user_role)
    {
        $this->authorize('view', $user_role);

        return Inertia::render('Admin/Role/Show', [
            'role' => $user_role->makeVisible(['allow_edit']),
            'permissions' => $user_role->UserRolePermissions->groupBy('UserRolePermissionTypes.group'),
        ]);
    }

    /**
     * Show the form for editing a Roles Permissions
     */
    public function edit(UserRoles $user_role)
    {
        $this->authorize('update', $user_role);

        return Inertia::render('Admin/Role/Edit', [
            'permission-list' => $user_role->UserRolePermissions->groupBy('UserRolePermissionTypes.group'),
            'role' => $user_role,
        ]);
    }

    /**
     * Update the roles Permissions
     */
    public function update(UserRoleRequest $request, UserRoles $user_role)
    {
        $request->updateExistingRole();

        Log::notice('User Role '.$user_role->name.' has been updated by '.$request->user()->username, $request->only(['name', 'description']));

        return back()->with('success', __('admin.user-roles.updated'));
    }

    /**
     * Remove a Role
     * Note:  A role cannot be removed if it is assigned to any number of users
     */
    public function destroy(Request $request, UserRoles $user_role)
    {
        $this->authorize('delete', $user_role);

        try {
            $user_role->delete();
        } catch (QueryException $e) {

            if(in_array($e->errorInfo[1], [19, 1451])) {
                throw new RecordInUseException(__('admin.user-roles.in-use'), 0, $e);
            }
        }

        Log::stack(['daily', 'user'])->notice('Role '.$user_role->name.' has been deleted by '.$request->user()->username);

        return redirect(route('admin.user-roles.index'))->with('warning', __('admin.user-roles.destroyed'));
    }
}

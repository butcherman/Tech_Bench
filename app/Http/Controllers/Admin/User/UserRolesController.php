<?php

namespace App\Http\Controllers\Admin\User;

use App\Events\Admin\UserRoleCreatedEvent;
use App\Events\Admin\UserRoleUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRoleRequest;
use App\Models\UserRolePermissions;
use App\Models\UserRolePermissionTypes;
use App\Models\UserRoles;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserRolesController extends Controller
{
    /**
     * Display a listing of the roles
     */
    public function index()
    {
        $this->authorize('viewAny', UserRoles::class);

        return Inertia::render('Admin/Roles/Index', [
            'roles' => UserRoles::all(),
        ]);
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        $this->authorize('create', UserRoles::class);

        return Inertia::render('Admin/Roles/Create', [
            'permissions' => UserRolePermissionTypes::all()->groupBy('group'),
        ]);
    }

    /**
     * Copy an existing role to a new role
     */
    public function copy(UserRoles $role)
    {
        $this->authorize('create', $role);

        return Inertia::render('Admin/Roles/Copy', [
            'description' => 'Copy of '.$role->name,
            'permissions' => UserRolePermissions::with('UserRolePermissionTypes')
                ->where('role_id', $role->role_id)
                ->get()
                ->groupBy('UserRolePermissionTypes.group'),
        ]);
    }

    /**
     * Store a newly created role
     */
    public function store(UserRoleRequest $request)
    {
        $newRole = UserRoles::create($request->only(['name', 'description']));
        Log::stack(['daily', 'user'])->info('New Role - '.$newRole->name.' create by '.$request->user()->username);

        UserRoleCreatedEvent::dispatch($newRole, $request->except(['name', 'description']));

        return redirect(route('admin.users.roles.index'))->with('success', __('admin.user.role_created'));
    }

    /**
     * Display the selected role
     */
    public function show(UserRoles $role)
    {
        $this->authorize('view', $role);

        return Inertia::render('Admin/Roles/Show', [
            'role' => $role->makeVisible('allow_edit'),
            'permissions' => UserRolePermissions::with('UserRolePermissionTypes')
                ->where('role_id', $role->role_id)
                ->get()
                ->groupBy('UserRolePermissionTypes.group'),
        ]);
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit(UserRoles $role)
    {
        $this->authorize('update', $role);

        return Inertia::render('Admin/Roles/Edit', [
            'role' => $role,
            'permissions' => UserRolePermissions::with('UserRolePermissionTypes')
                ->where('role_id', $role->role_id)
                ->get()
                ->groupBy('UserRolePermissionTypes.group'),
        ]);
    }

    /**
     * Update the specified role
     */
    public function update(UserRoleRequest $request, UserRoles $role)
    {
        $role->update($request->only(['name', 'description']));
        Log::stack(['daily', 'user'])->info('Role - '.$role->name.' has been updated by '.$request->user()->username);

        UserRoleUpdatedEvent::dispatch($role, $request->except(['name', 'description']));

        return redirect(route('admin.users.roles.show', $role->role_id))->with('success', __('admin.user.role_updated'));
    }

    /**
     * Remove the specified role
     */
    public function destroy(UserRoles $role)
    {
        $this->authorize('delete', $role);

        try {
            $role->delete();
        } catch (QueryException $e) {
            if ($e->errorInfo[1] === 19 || $e->errorInfo[1] === 1451) {
                Log::stack(['daily', 'user'])->error('Unable to delete Role '.$role->name.'.  It is currently in use');

                return back()->withErrors([
                    'error' => __('admin.user.role_in_use'),
                ]);
            }

            // @codeCoverageIgnoreStart
            Log::stack(['daily', 'user'])->error('Error when trying to delete Role '.$role->name, $e->errorInfo);

            return back()->withErrors(['error' => 'Error when trying to delete Role '.$role->name.' Please see log for details']);
            // @codeCoverageIgnoreEnd
        }

        Log::stack(['daily', 'user'])->notice('Role '.$role->name.' has been deleted by '.Auth::user()->username);

        return redirect(route('admin.users.roles.index'))->with('success', __('admin.user.role_deleted'));
    }
}

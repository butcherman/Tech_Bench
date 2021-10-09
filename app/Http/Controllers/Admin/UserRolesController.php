<?php

namespace App\Http\Controllers\Admin;

use App\Events\Admin\UserRoleCreatedEvent;
use App\Events\Admin\UserRoleDeletedEvent;
use App\Events\Admin\UserRoleUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRoleRequest;
use App\Models\User;
use App\Models\UserRolePermissions;
use App\Models\UserRolePermissionTypes;
use App\Models\UserRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserRolesController extends Controller
{
    /**
     * Show the existing User Roles
     */
    public function index()
    {
        $this->authorize('viewAny', UserRoles::class);

        return Inertia::render('Admin/Roles/Index', [
            'roles' => UserRoles::all()->makeVisible('allow_edit'),
        ]);
    }

    /**
     * Show the form to create a new Role
     */
    public function create()
    {
        $this->authorize('create', UserRoles::class);

        return Inertia::render('Admin/Roles/Create', [
            'permissions' => UserRolePermissionTypes::all(),
        ]);
    }

    /**
     * Create the new User Role
     */
    public function store(UserRoleRequest $request)
    {
        //  Create the new role
        $newRole = UserRoles::create($request->only(['name', 'description']));

        //  Insert the permissions for the role
        foreach($request->user_role_permissions as $perm)
        {
            UserRolePermissions::create([
                'role_id'      => $newRole->role_id,
                'perm_type_id' => $perm['perm_type_id'],
                'allow'        => isset($perm['allow']) ? $perm['allow'] : false,
            ]);
        }

        event(new UserRoleCreatedEvent($newRole));
        return redirect(route('admin.user-roles.index'))->with([
            'message' => 'New role created',
            'type'    => 'success',
        ]);
    }

    /**
     * Show the form for editing the User Role
     */
    public function edit($id)
    {
        $role = UserRoles::with('UserRolePermissions.UserRolePermissionTypes')->where('role_id', $id)->firstOrFail();
        $this->authorize('update', $role);

        return Inertia::render('Admin/Roles/Edit', [
            'role_data' => $role,
        ]);
    }

    /**
     * Update the user Role
     */
    public function update(UserRoleRequest $request, $id)
    {
        //  Block a user from trying to update one of the default roles
        if($id <= 4)
        {
            report('User '.$request->user()->username.' is trying to modify a default role');
            abort(403, 'You cannot modify a default User Role');
        }

        //  Update the role details
        $role = UserRoles::find($id);
        $role->update($request->only(['name', 'description']));

        //  Update the role permissions
        foreach($request->user_role_permissions as $perm)
        {
            UserRolePermissions::where(['role_id' => $perm['role_id'], 'perm_type_id' => $perm['perm_type_id']])->update([
                'allow' => $perm['allow']
            ]);
        }

        event(new UserRoleUpdatedEvent($role));
        return redirect(route('admin.user-roles.index'))->with([
            'message' => 'Role Updated',
            'type'    => 'success'
        ]);
    }

    /**
     * Remove a User Role
     */
    public function destroy($id)
    {
        $role = UserRoles::findOrFail($id);
        $this->authorize('forceDelete', $role);

        //  verify this is not a default role
        $role = UserRoles::find($id);
        if(!$role->allow_edit)
        {
            report('User '.Auth::user()->full_name.' is trying to delete one of the default User Roles');
            return back()->with([
                'message' => 'You cannot delete one of the default User Roles',
                'type' => 'danger'
            ]);
        }

        //  Verify that it is not in use
        $inUse = User::where('role_id', $id)->count();
        if($inUse)
        {
            report('User '.Auth::user()->username.' is trying to delete a role that is stil in use.  Details');
            return back()->with([
                'message' => 'This User Role is in use.  Please remove all users from this role before deleting',
                'type' => 'danger'
            ]);
        }

        $role->delete();

        event(new UserRoleDeletedEvent($role));
        return redirect(route('admin.user-roles.index'));
    }
}

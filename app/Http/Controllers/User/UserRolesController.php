<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;

use App\Models\User;
use App\Models\UserRoles;
use App\Models\UserRolePermissions;
use App\Http\Controllers\Controller;
use App\Models\UserRolePermissionTypes;
use App\Http\Requests\User\UserRoleRequest;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserRolesController extends Controller
{
    /**
     *  Bring up the list os available roles that exist
     */
    public function index()
    {
        $this->authorize('view', UserRolePermissions::class);

        return Inertia::render('User/Roles/index', [
            'roles' => UserRoles::all()->makeVisible('allow_edit'),
        ]);
    }

    /**
     *  Create new role form
     */
    public function create()
    {
        $this->authorize('create', UserRolePermissions::class);

        return Inertia::render('User/Roles/new', [
            'permissions' => UserRolePermissionTypes::all(),
        ]);
    }

    /**
     *  Create a new User Role
     */
    public function store(UserRoleRequest $request)
    {
        $this->authorize('create', UserRolePermissions::class);

        //  Create the new role
        $newRole = UserRoles::create($request->only(['name', 'description']));

        //  Insert the permissions
        foreach($request->user_role_permissions as $perm)
        {
            UserRolePermissions::create([
                'role_id'      => $newRole->role_id,
                'perm_type_id' => $perm['perm_type_id'],
                'allow'        => isset($perm['allow']) ? $perm['allow'] : false,
            ]);
        }

        Log::info('New User Role '.$newRole->name.' has been created by '.Auth::user()->full_name);
        return redirect(route('admin.user-roles.index'))->with(['message' => 'New User Role Created', 'type' => 'success']);
    }

    /**
     *  Edit an existing user role
     */
    public function edit($id)
    {
        $this->authorize('update', UserRolePermissions::class);

        return Inertia::render('User/Roles/edit', [
            'role_data' => UserRoles::with('UserRolePermissions.UserRolePermissionTypes')->where('role_id', $id)->first(),
        ]);
    }

    /**
     *  Update the existing role
     */
    public function update(UserRoleRequest $request, $id)
    {
        $this->authorize('update', UserRolePermissions::class);

        //  If the user is trying to update one of the built in policies, it will be denied
        if($id < 5)
        {
            Log::alert('User '.Auth::user()->full_name.' is trying to modify a default User Role');
            abort(403, 'You cannot modify a default User Role');
        }

        //  Update the primary role
        UserRoles::find($id)->update($request->only(['name', 'description']));

        foreach($request->user_role_permissions as $perm)
        {
            UserRolePermissions::where(['role_id' => $perm['role_id'], 'perm_type_id' => $perm['perm_type_id']])->update(['allow' => $perm['allow']]);
        }

        Log::info('User Role ID '.$id.' Name - '.$request->name.' has been updated by '.Auth::user()->full_name);
        return back()->with(['message' => 'Role Updated', 'type' => 'success']);
    }

    /**
     *  Delete a user role
     */
    public function destroy($id)
    {
        $this->authorize('create', UserRolePermissions::class);

        //  verify this is not a default role
        $role = UserRoles::find($id);
        if(!$role->allow_edit)
        {
            Log::alert('User '.Auth::user()->full_name.' is trying to delete one of the default User Roles');
            return back()->with(['message' => 'You cannot delete one of the default User Roles', 'type' => 'danger']);
        }

        //  Verify that it is not in use
        $inUse = User::where('role_id', $id)->count();

        if($inUse)
        {
            return back()->with(['message' => 'This User Role is in use.  Please remove all users from this role before deleting', 'type' => 'danger']);
        }

        $role->delete();

        Log::notice('User Role ID '.$id.' Name '.$role->name.' has been deleted by '.Auth::user()->full_name);
        return redirect(route('admin.user-roles.index'));
    }
}

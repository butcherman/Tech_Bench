<?php

namespace App\Http\Controllers\Admin;

use App\Events\Admin\UserRoleCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRoleRequest;
use App\Models\UserRolePermissions;
use App\Models\UserRolePermissionTypes;
use App\Models\UserRoles;
use Illuminate\Http\Request;
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
            'role'        => $role,
            'permissions' => UserRolePermissions::where('role_id', $role->role_id)->get()->groupBy('group'),
        ]);
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit($id)
    {
        return Inertia::render('Admin/Roles/Edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\UserPermissionsRequest;
use App\Models\User;
use App\Models\UserRolePermissionTypes;
use Inertia\Inertia;

class UserPermissionsReportController extends Controller
{
    /**
     * User Permissions Report landing page
     */
    public function index()
    {
        return Inertia::render('Reports/UserPermissions/Index', [
            'user_list' => User::all(),
        ]);
    }

    /**
     * Redirect to report index page
     */
    public function show($id)
    {
        return redirect(route('reports.user-permissions-report.index'));
    }

    /**
     * Run the User Permissions Report
     */
    public function update(UserPermissionsRequest $request, $id)
    {
        $userList    = [];
        $permissions = UserRolePermissionTypes::all()->groupBy('group');
        foreach($request->selected_list as $u)
        {
            $user = User::where('username', $u)->with('UserRoles.UserRolePermissions')->first();
            $userList[] = $user;
        }

        return Inertia::render('Reports/UserPermissions/Report', [
            'data'        => $userList,
            'permissions' => $permissions,
        ]);
    }
}

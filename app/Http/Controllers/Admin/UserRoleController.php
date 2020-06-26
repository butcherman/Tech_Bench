<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewRoleRequest;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Domains\Roles\GetRoles;
use App\Domains\Roles\GetPermissions;
use App\Domains\Roles\SetPermissions;

class UserRoleController extends Controller
{
    public function permissionSettings()
    {
        return view('admin.userPermissions', [
            'roles' => (new GetRoles)->getRoleList(false),
            'perms' => (new GetPermissions)->getAllPermissions(),
        ]);
    }

    public function submitRole(NewRoleRequest $request)
    {
        (new SetPermissions)->submitRole($request);
        Log::notice('User Permissions Role '.$request->name.' has been updated by '.Auth::user()->full_name.'.  Data - ', $request->toArray());
        return response()->json(['success' => true]);
    }
}

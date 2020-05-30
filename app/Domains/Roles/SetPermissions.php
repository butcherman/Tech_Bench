<?php

namespace App\Domains\Roles;

use Illuminate\Support\Facades\Log;

use App\UserRolePermissions;

use App\UserRoleType;

class SetPermissions
{
    public function submitRole($request)
    {
        if($request->role_id != null)
        {
            $this->updateRole($request);
        }
        else
        {
            $this->createRole($request);
        }

        return true;
    }

    protected function updateRole($request)
    {
        UserRoleType::findOrFail($request->role_id)->update([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        foreach($request->user_role_permissions as $perm)
        {
            Log::emergency($perm);
            UserRolePermissions::where('role_id', $request->role_id)->where('perm_type_id', $perm['perm_type_id'])->update([
                'allow' => $perm['allow'],
            ]);
        }
    }

    protected function createRole($request)
    {
        $role = UserRoleType::create([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        foreach($request->user_role_permissions as $perm)
        {
            UserRolePermissions::create([
                'role_id'      => $role['role_id'],
                'perm_type_id' => $perm['perm_type_id'],
                'allow'        => isset($perm['allow']) ? true : false,
            ]);
        }
    }
}

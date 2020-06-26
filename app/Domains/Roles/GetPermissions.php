<?php

namespace App\Domains\Roles;

use App\UserRolePermissionTypes;

use Illuminate\Support\Facades\Log;

class GetPermissions
{
    public function getAllPermissions()
    {
        return UserRolePermissionTypes::all();
    }
}

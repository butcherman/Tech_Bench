<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRolePermission extends Model
{
    use HasFactory;

    /**
     * User Role Permissions explain what a permission type id is for
     */
    public function UserRolePermissionType()
    {
        return $this->hasOne(UserRolePermissionType::class, 'perm_type_id', 'perm_type_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRolePermissions extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['role_id', 'created_at', 'updated_at', 'id'];

    protected $casts = [
        'allow' => 'boolean',
    ];

    /**
     * User Role Permissions explain what a permission type id is for
     */
    public function UserRolePermissionTypes()
    {
        return $this->hasOne('App\Models\UserRolePermissionTypes', 'perm_type_id', 'perm_type_id');
    }
}

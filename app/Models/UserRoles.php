<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    use HasFactory;

    protected $primaryKey = 'role_id';
    protected $guarded    = ['role_id', 'allow_edit', 'created_at', 'updated_at'];
    protected $hidden     = ['allow_edit', 'created_at', 'updated_at'];

    // public function UserRolePermissionTypes()
    // {
    //     // return $this->hasMany('App\Models\UserRolePermissionTypes', 'perm_type_id', 'perm_type_id');
    //     return $this->hasManyThrough('App\Models\UserRolePermissionTypes', 'App\Models\UserRolePermissions', 'role_id', 'perm_type_id', 'role_id', 'perm_type_id');
    // }

        public function UserRolePermissions()
        {
            return $this->hasMany('App\Models\UserRolePermissions', 'role_id', 'role_id');
        }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRolePermissions extends Model
{
    //
    protected $fillable = ['perm_type_id', 'role_id', 'allow'];

    public function UserRolePermissionTypes()
    {
        return $this->hasOne('App\UserRolePermissionTypes', 'perm_type_id', 'perm_type_id');
    }

    public function UserRoles()
    {
        return $this->belongsTo('App\UserRoles', 'role_id', 'role_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRolePermissions extends Model
{
    protected $fillable = ['perm_type_id', 'role_id', 'allow'];
    protected $hidden   = ['created_at', 'updated_at', 'id', 'UserRolePermissionTypes'];
    protected $appends  = ['description'];

    public function getDescriptionAttribute()
    {
        return $this->UserRolePermissionTypes->description;
    }

    public function UserRolePermissionTypes()
    {
        return $this->hasOne('App\UserRolePermissionTypes', 'perm_type_id', 'perm_type_id');
    }
}

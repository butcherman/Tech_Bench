<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRoleType extends Model
{
    //
    protected $primaryKey = 'role_id';
    protected $fillable   = ['name', 'description'];
    protected $hidden     = ['created_at', 'updated_at'];

    public function UserRolePermissions()
    {
        return $this->hasMany('App\UserRolePermissions', 'role_id', 'role_id');
    }

    public function UserRolePermissionTypes()
    {
        return $this->belongsTo('App\UserRolePermissionTypes', 'perm_type_id', 'perm_type_id');
    }
}

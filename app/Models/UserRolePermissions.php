<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRolePermissions extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $hidden  = ['created_at', 'updated_at', 'id'];

    public function UserRolePermissionTypes()
    {
        return $this->hasOne('App\Models\UserRolePermissionTypes', 'perm_type_id', 'perm_type_id');
    }
}

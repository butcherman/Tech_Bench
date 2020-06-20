<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRolePermissionTypes extends Model
{
    protected $primaryKey = 'perm_type_id';
    protected $hidden     = ['created_at', 'updated_at'];
    protected $casts      = ['allow' => 'boolean'];
}

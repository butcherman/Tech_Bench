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
    protected $casts      = [
        'allow_edit' => 'boolean',
    ];

    /*
    *   Each User Role is assigned several allow or deny permissions to control user access
    */
    public function UserRolePermissions()
    {
        return $this->hasMany('App\Models\UserRolePermissions', 'role_id', 'role_id');
    }
}

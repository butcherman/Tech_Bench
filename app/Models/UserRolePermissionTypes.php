<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRolePermissionTypes extends Model
{
    use HasFactory;

    protected $primaryKey = 'perm_type_id';

    public function UserRolePermissions()
    {
        return $this->belongsTo('App\Models\UserRolePermissions', 'perm_type_id', 'perm_type_id');
    }
}

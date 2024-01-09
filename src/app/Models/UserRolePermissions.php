<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRolePermissions extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['role_id', 'created_at', 'updated_at', 'id', 'UserRolePermissionTypes'];

    protected $casts = [
        'allow' => 'boolean',
    ];
}

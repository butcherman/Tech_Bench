<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRolePermissionType extends Model
{
    use HasFactory;

    protected $primaryKey = 'perm_type_id';

    protected $hidden = ['created_at', 'is_admin_link', 'updated_at', 'role_cat_id'];

    protected $guarded = ['perm_type_id', 'created_at', 'updated_at'];

    protected $appends = ['group'];
}

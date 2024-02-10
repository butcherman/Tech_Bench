<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRolePermissionCategory extends Model
{
    protected $primaryKey = 'role_cat_id';

    protected $guarded = ['role_cat_id', 'created_at', 'updated_at'];

    protected $hidden = ['role_cat_id', 'created_at', 'updated_at'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRolePermissionCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'role_cat_id';
    protected $guarded    = ['created_at', 'updated_at'];
    protected $hidden     = ['role_cat_id', 'created_at', 'updated_at'];
}

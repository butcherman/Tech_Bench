<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRolePermissionCategory extends Model
{
    /** @var string */
    protected $primaryKey = 'role_cat_id';

    /** @var array<int, string> */
    protected $guarded = ['role_cat_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['role_cat_id', 'created_at', 'updated_at'];
}

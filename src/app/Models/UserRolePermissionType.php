<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRolePermissionType extends Model
{
    /** @var string */
    protected $primaryKey = 'perm_type_id';

    /** @var array<int, string> */
    protected $hidden = [
        'role_cat_id',
        'is_admin_link',
        'created_at',
        'updated_at',
        'UserRolePermissionCategory',
    ];

    /** @var array<int, string> */
    protected $guarded = ['perm_type_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $appends = ['group', 'feature_enabled'];
}

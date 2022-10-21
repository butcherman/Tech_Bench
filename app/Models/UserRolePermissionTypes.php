<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRolePermissionTypes extends Model
{
    use HasFactory;

    protected $primaryKey = 'perm_type_id';
    protected $hidden     = ['created_at', 'is_admin_link', 'updated_at', 'UserRolePermissionCategory', 'role_cat_id'];
    protected $guarded    = ['perm_type_id', 'created_at', 'updated_at'];
    protected $appends    = ['group'];

    /**
     * Get the Group name of the permission type
     * @codeCoverageIgnore
     */
    public function getGroupAttribute()
    {
        $cat = $this->UserRolePermissionCategory;
        return $cat ? $cat->category : null;
    }

    /**
     * User Role Categories allow for grouping of the permission types
     */
    public function UserRolePermissionCategory()
    {
        return $this->hasOne('App\Models\UserRolePermissionCategory', 'role_cat_id', 'role_cat_id');
    }
}

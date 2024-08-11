<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserRolePermissionType extends Model
{
    protected $primaryKey = 'perm_type_id';

    protected $hidden = [
        'role_cat_id',
        'is_admin_link',
        'created_at',
        'updated_at',
        'UserRolePermissionCategory',
    ];

    protected $guarded = []; // ['perm_type_id', 'created_at', 'updated_at'];

    protected $appends = ['group', 'feature_enabled'];

    /**
     * Model Attributes
     */
    public function getGroupAttribute()
    {
        return $this->UserRolePermissionCategory ?
            $this->UserRolePermissionCategory->category : null;
    }

    public function getFeatureEnabledAttribute()
    {
        if ($this->feature_name) {
            return Auth::user()
                ->features()
                ->active($this->feature_name);
        }

        return true;
    }

    /**
     * Model Relationships
     */
    public function UserRolePermissionCategory()
    {
        return $this->belongsTo(
            UserRolePermissionCategory::class,
            'role_cat_id',
            'role_cat_id'
        );
    }
}

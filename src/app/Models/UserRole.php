<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    protected $primaryKey = 'role_id';

    protected $guarded = ['role_id', 'allow_edit', 'created_at', 'updated_at'];

    protected $hidden = [
        'allow_edit',
        'created_at',
        'updated_at',
        'UserRolePermission',
    ];

    protected $casts = [
        'allow_edit' => 'boolean',
    ];

    /***************************************************************************
     * Additional Attributes
     ***************************************************************************/
    public function getHrefAttribute()
    {
        return route('admin.user-roles.show', $this->role_id);
    }

    /***************************************************************************
     * Model Relationships
     ***************************************************************************/
    public function UserRolePermission()
    {
        return $this->hasMany(UserRolePermission::class, 'role_id', 'role_id');
    }
}

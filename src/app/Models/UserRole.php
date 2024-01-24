<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    protected $primaryKey = 'role_id';

    protected $guarded = ['role_id', 'allow_edit', 'created_at', 'updated_at'];

    protected $hidden = ['allow_edit', 'created_at', 'updated_at', 'UserRolePermission'];

    protected $casts = [
        'allow_edit' => 'boolean',
    ];

    /**
     * Key for Route/Model binding
     */
    public function getRouteKeyName()
    {
        return 'role_id';
    }

    public function UserRolePermissions()
    {
        return $this->hasMany(UserRolePermission::class, 'role_id', 'role_id');
    }
}

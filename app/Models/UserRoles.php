<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    use HasFactory;

    protected $primaryKey = 'role_id';

    protected $guarded = ['role_id', 'allow_edit', 'created_at', 'updated_at'];

    protected $hidden = ['allow_edit', 'created_at', 'updated_at', 'UserRolePermissions'];

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
        return $this->hasMany(UserRolePermissions::class, 'role_id', 'role_id');
    }
}

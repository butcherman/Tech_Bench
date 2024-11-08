<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserRolePermission extends Model
{
    /** @var string */
    protected $primaryKey = 'id';

    /** @var array<int, string> */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = [
        'id',
        'role_id',
        'created_at',
        'updated_at',
        'UserRolePermissionType',
    ];

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'allow' => 'boolean',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function UserRolePermissionType(): HasOne
    {
        return $this->hasOne(
            UserRolePermissionType::class,
            'perm_type_id',
            'perm_type_id'
        );
    }
}

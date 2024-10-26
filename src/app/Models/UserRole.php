<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    /** @var string */
    protected $primaryKey = 'role_id';

    /** @var array<int, string> */
    protected $guarded = ['role_id', 'allow_edit', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = [
        'allow_edit',
        'created_at',
        'updated_at',
        'UserRolePermission',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'allow_edit' => 'boolean',
    ];
}

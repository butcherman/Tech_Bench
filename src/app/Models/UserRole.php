<?php

namespace App\Models;

use App\Observers\UserRoleObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([UserRoleObserver::class])]
class UserRole extends Model
{
    use HasFactory;

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

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'allow_edit' => 'boolean',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function UserRolePermission()
    {
        return $this->hasMany(UserRolePermission::class, 'role_id', 'role_id');
    }
}

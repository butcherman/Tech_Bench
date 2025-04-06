<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

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
    protected $appends = ['group'];

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function group(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->UserRolePermissionCategory->category ?? null,
        );
    }

    // TODO - Go Away???
    // public function featureEnabled(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn () => $this->feature_name && Auth::check()
    //             ? Auth::user()->features()->active($this->feature_name)
    //             : true,
    //     );
    // }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function UserRolePermissionCategory(): BelongsTo
    {
        return $this->belongsTo(
            UserRolePermissionCategory::class,
            'role_cat_id',
            'role_cat_id'
        );
    }
}

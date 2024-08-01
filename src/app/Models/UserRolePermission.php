<?php

namespace App\Models;

use App\Events\Feature\FeatureChangedEvent;
use Illuminate\Database\Eloquent\Model;

class UserRolePermission extends Model
{
    protected $primaryKey = 'id';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = [
        'id',
        'role_id',
        'created_at',
        'updated_at',
        'UserRolePermissionType',
    ];

    protected $appends = ['description'];

    protected $casts = [
        'allow' => 'boolean',
    ];

    protected $dispatchesEvents = [
        'created' => FeatureChangedEvent::class,
        'updated' => FeatureChangedEvent::class,
        'deleted' => FeatureChangedEvent::class,
    ];

    /**
     * Additional Attributes
     */
    public function getDescriptionAttribute()
    {
        return $this->UserRolePermissionType->description;
    }

    /**
     * Model Relationships
     */
    public function UserRolePermissionType()
    {
        return $this->hasOne(UserRolePermissionType::class, 'perm_type_id', 'perm_type_id');
    }
}

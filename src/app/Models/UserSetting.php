<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $primaryKey = 'setting_id';

    protected $guarded = ['setting_id', 'created_at', 'updated_at'];

    protected $hidden = [
        'setting_id',
        'user_id',
        'created_at',
        'updated_at',
        'UserSettingType',
    ];

    protected $appends = ['name'];

    protected $casts = [
        'value' => 'boolean',
    ];

    /***************************************************************************
     * Model Attributes
     ***************************************************************************/
    public function getNameAttribute()
    {
        return $this->UserSettingType->name;
    }

    /***************************************************************************
     * Model Relationships
     ***************************************************************************/
    public function UserSettingType()
    {
        return $this->hasOne(UserSettingType::class, 'setting_type_id', 'setting_type_id');
    }
}

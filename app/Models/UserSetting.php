<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $primaryKey = 'setting_id';

    protected $guarded = ['setting_id', 'created_at', 'updated_at'];

    protected $hidden = ['setting_id', 'user_id', 'created_at', 'updated_at', 'user_setting_type'];

    protected $appends = ['name'];

    protected $casts = [
        'value' => 'boolean',
    ];

    /**
     * The Name attribute returns the UserSettingType Name column
     */
    public function getNameAttribute()
    {
        return $this->UserSettingType->name;
    }

    /**
     * Each setting is tied to a setting type id
     *
     */
    public function UserSettingType()
    {
        return $this->hasOne(UserSettingType::class, 'setting_type_id', 'setting_type_id');
    }
}

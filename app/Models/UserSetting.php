<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $primaryKey = 'setting_id';
    protected $guarded    = ['setting_id', 'created_at', 'updated_at'];
    protected $hidden     = ['setting_id', 'user_id', 'created_at', 'updated_at'];
    protected $casts      = [
        'value' => 'boolean',
    ];

    public function UserSettingType()
    {
        return $this->hasOne(UserSettingType::class, 'setting_type_id', 'setting_type_id');
    }
}

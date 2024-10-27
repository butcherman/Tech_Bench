<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    /** @var string */
    protected $primaryKey = 'setting_id';

    /** @var array<int, string> */
    protected $guarded = ['setting_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = [
        'setting_id',
        'user_id',
        'created_at',
        'updated_at',
        'UserSettingType',
    ];
}

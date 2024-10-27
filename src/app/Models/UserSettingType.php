<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSettingType extends Model
{
    /** @var string */
    protected $primaryKey = 'setting_type_id';

    /** @var array<int, string> */
    protected $guarded = ['setting_type_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['perm_type_id', 'created_at', 'updated_at'];
}

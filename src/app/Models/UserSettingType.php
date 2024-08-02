<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSettingType extends Model
{
    use HasFactory;

    protected $primaryKey = 'setting_type_id';

    protected $guarded = ['setting_type_id', 'created_at', 'updated_at'];

    protected $hidden = ['perm_type_id', 'created_at', 'updated_at'];
}

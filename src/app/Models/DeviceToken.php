<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    protected $primaryKey = 'device_id';

    protected $guarded = ['device_id', 'created_at', 'updated_at'];

    protected $hidden = ['user_id', 'token'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
    ];
}

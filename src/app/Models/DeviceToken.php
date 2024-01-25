<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    protected $primaryKey = 'device_id';

    protected $guarded = ['device_id', 'created_at', 'updated_at'];
}

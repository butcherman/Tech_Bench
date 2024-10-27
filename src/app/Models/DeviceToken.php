<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    use HasFactory;

    /** @var string */
    protected $primaryKey = 'device_id';

    /** @var array<int, string> */
    protected $guarded = ['device_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['user_id', 'token'];

    /** @var array<string, string> */
    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLogins extends Model
{
    protected $fillable = ['user_id', 'ip_address'];
    protected $casts    = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
    ];
}

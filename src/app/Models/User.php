<?php

namespace App\Models;

use App\Traits\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use CanResetPassword;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    protected $primaryKey = 'user_id';

    protected $guarded = ['created_at', 'updated_at'];

    protected $hidden = [
        'role_id',
        'password',
        'remember_token',
        // 'two_factor_secret',
        // 'two_factor_recovery_codes',
        // 'two_factor_confirmed_at',
        'deleted_at',
        'created_at',
        'password_expires',
        'updated_at',
        'user_id',
        'phone',
        'receive_sms',
        'sms_verified',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
    ];
}

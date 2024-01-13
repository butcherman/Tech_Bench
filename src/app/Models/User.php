<?php

namespace App\Models;

use App\Traits\Notifiable;
use Carbon\Carbon;
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
        'deleted_at',
        'created_at',
        'password_expires',
        'updated_at',
        'user_id',
        // 'phone',
        // 'receive_sms',
        // 'sms_verified',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
    ];

    protected $appends = ['full_name', 'initials'];

    /**
     * Additional User Attributes
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getInitialsAttribute()
    {
        return "{$this->first_name[0]}{$this->last_name[0]}";
    }

    /**
     * Determine the new expire date for an updated password
     */
    public function getNewExpireTime($immediate = false)
    {
        if ($immediate) {
            return Carbon::yesterday();
        }

        return config('auth.passwords.settings.expire') ?
            Carbon::now()->addDays(config('auth.passwords.settings.expire')) : null;
    }
}

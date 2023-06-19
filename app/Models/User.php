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
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use CanResetPassword;

    protected $primaryKey = 'user_id';

    protected $guarded = ['created_at', 'updated_at'];

    protected $hidden = ['role_id', 'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at', 'deleted_at', 'created_at', 'password_expires', 'updated_at', 'user_id'];

    protected $appends = ['full_name', 'initials'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
    ];

    /**
     * Key for Route/Model binding
     */
    public function getRouteKeyName()
    {
        return 'username';
    }

    /**
     * Users First and Last name combined
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * User Initials
     */
    public function getInitialsAttribute()
    {
        return "{$this->first_name[0]}{$this->last_name[0]}";
    }

    /**
     * Each user is assigned to a role that determines what permissions they are allowed
     */
    public function UserRole()
    {
        return $this->hasOne('App\Models\UserRoles', 'role_id', 'role_id');
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

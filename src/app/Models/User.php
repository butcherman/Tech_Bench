<?php

namespace App\Models;

use App\Notifications\User\SendAuthCode;
use App\Traits\Notifiable;
use Carbon\Carbon;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Notification;

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
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
    ];

    protected $appends = ['full_name', 'initials'];

    /**
     * Key for Route/Model binding
     */
    public function getRouteKeyName()
    {
        return 'username';
    }

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

    /**
     * Generate a 2FA Code
     */
    public function generateVerificationCode()
    {
        $code = rand(0000, 9999);

        UserCode::updateOrCreate(
            ['user_id' => $this->user_id],
            ['code' => $code],
        );

        Notification::send($this, new SendAuthCode($code));
    }
}

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
use Illuminate\Support\Str;

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

    /**
     * Generate a 2FA Code
     */
    public function generateVerificationCode($verify = false)
    {
        $code = rand(0000, 9999);
        $toSms = $this->receive_sms && ($this->sms_verified || $verify);

        UserCode::updateOrCreate(
            ['user_id' => $this->user_id],
            ['code' => $code],
        );

        Notification::send($this, new SendAuthCode($code, $toSms));
    }

    /**
     * Generate a Remember Me token for a device
     */
    public function generateRememberDeviceToken()
    {
        $token = Str::random(60);
        DeviceToken::create([
            'user_id' => $this->user_id,
            'token' => $token,
        ]);

        return $token;
    }

    /**
     * Validate a Remember Me device token
     */
    public function validateDeviceToken($token)
    {
        $valid = DeviceToken::where('user_id', $this->user_id)->where('token', $token)->first();

        return $valid ? true : false;
    }
}

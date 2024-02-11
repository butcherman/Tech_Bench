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
use Karmendra\LaravelAgentDetector\AgentDetector;

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
        'UserRole',
    ];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
    ];

    protected $appends = ['full_name', 'initials', 'role_name'];

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

    public function getRoleNameAttribute()
    {
        return $this->UserRole->name;
    }

    /**
     * Relationships
     */
    public function UserRole()
    {
        return $this->hasOne(UserRole::class, 'role_id', 'role_id');
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
        $code = rand(1000, 9999);

        UserVerificationCode::updateOrCreate(
            ['user_id' => $this->user_id],
            ['code' => $code],
        );

        Notification::send($this, new SendAuthCode($code));
    }

    /**
     * Generate a Remember token for a device
     *
     * @codeCoverageIgnore
     */
    public function generateRememberDeviceToken()
    {
        $token = Str::random(60);
        $agent = new AgentDetector($_SERVER['HTTP_USER_AGENT']);
        $ipAddr = \Request::ip();

        DeviceToken::create([
            'user_id' => $this->user_id,
            'token' => $token,
            'type' => $agent->device(),
            'os' => $agent->platform().' '.$agent->platformVersion(),
            'browser' => $agent->browser(),
            'registered_ip_address' => $ipAddr,
            'updated_ip_address' => $ipAddr,
        ]);

        return $token;
    }

    /**
     * Validate a Remember Me device token
     *
     * @codeCoverageIgnore
     */
    public function validateDeviceToken($token)
    {
        $valid = DeviceToken::where('user_id', $this->user_id)->where('token', $token)->first();
        if ($valid) {
            $valid->update(['updated_ip_address' => \Request::ip()]);

            return true;
        }

        return false;
    }

    /**
     * Function to get login history for the last xx days
     *
     * @codeCoverageIgnore
     */
    public function getLoginHistory($days = 365)
    {
        return UserLogins::whereUserId($this->user_id)
            ->where('created_at', '>', now()->subDays($days))
            ->get();
    }
}

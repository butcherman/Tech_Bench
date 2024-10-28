<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /** @var string */
    protected $primaryKey = 'user_id';

    /** @var array<int, string> */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /** @var array<int, string> */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** @var array<string, string> */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = ['initials', 'full_name'];

    /*
    |---------------------------------------------------------------------------
    | Route Model Binding Key
    |---------------------------------------------------------------------------
    */
    public function getRouteKeyName()
    {
        return 'username';
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name.' '.$this->last_name,
        );
    }

    public function initials(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name[0].$this->last_name[0],
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function DeviceToken(): HasMany
    {
        return $this->hasMany(DeviceToken::class, 'user_id', 'user_id');
    }

    public function UserVerificationCode()
    {
        return $this->hasOne(UserVerificationCode::class, 'user_id', 'user_id');
    }

    public function UserLogins()
    {
        return $this->hasMany(UserLogin::class, 'user_id', 'user_id');
    }

    /*
    |---------------------------------------------------------------------------
    | Additional Model Methods
    |---------------------------------------------------------------------------
    */

    /**
     * Determine the new expire date for an updated password
     */
    public function getNewExpireTime(?bool $immediate = false): ?Carbon
    {
        if ($immediate) {
            return Carbon::yesterday();
        }

        return config('auth.passwords.settings.expire')
            ? Carbon::now()->addDays(config('auth.passwords.settings.expire'))
            : null;
    }

    /**
     * Generate a 2FA Code
     */
    public function generateVerificationCode()
    {
        UserVerificationCode::updateOrCreate(
            ['user_id' => $this->user_id],
            ['code' => rand(1000, 9999)],
        );
    }

    /**
     * Validate a Remember Me device token
     */
    public function validateDeviceToken(string $token): bool
    {
        $valid = $this->DeviceToken->where('token', $token)->first();

        if ($valid) {
            $valid->update(['updated_ip_address' => request()->ip()]);

            return true;
        }

        return false;
    }
}

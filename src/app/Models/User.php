<?php

namespace App\Models;

use App\Observers\UserObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /** @var string */
    protected $primaryKey = 'user_id';

    /** @var array<int, string> */
    protected $guarded = ['user_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
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

    /** @var array<string, string> */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:M d, Y',
            'updated_at' => 'datetime:M d, Y',
            'deleted_at' => 'datetime:M d, Y',
            'password_expires' => 'datetime: M d, Y',
        ];
    }

    /** @var array<string, string> */
    protected $appends = ['initials', 'full_name'];

    /*
    |---------------------------------------------------------------------------
    | Route Model Binding Key
    |---------------------------------------------------------------------------
    */
    public function getRouteKeyName(): string
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
    public function DeviceTokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class, 'user_id', 'user_id');
    }

    public function UserVerificationCode(): HasOne
    {
        return $this->hasOne(UserVerificationCode::class, 'user_id', 'user_id');
    }

    public function UserLogins(): HasMany
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
    public function generateVerificationCode(): void
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
        $valid = $this->DeviceTokens->where('token', $token)->first();

        if ($valid) {
            $valid->update(['updated_ip_address' => request()->ip()]);

            return true;
        }

        return false;
    }
}

<?php

namespace App\Models;

use App\Observers\UserObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Pennant\Concerns\HasFeatures;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
{
    use HasFactory;
    use HasFeatures;
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
    protected $appends = ['initials', 'full_name', 'role_name'];

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:M d, Y',
            'updated_at' => 'datetime:M d, Y',
            'deleted_at' => 'datetime:M d, Y',
            'password_expires' => 'datetime: M d, Y',
        ];
    }

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
            get: fn() => $this->first_name . ' ' . $this->last_name,
        );
    }

    public function initials(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->first_name[0] . $this->last_name[0],
        );
    }

    public function roleName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->UserRole->name,
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

    public function UserSettings(): HasMany
    {
        return $this->hasMany(UserSetting::class, 'user_id', 'user_id');
    }

    public function UserRole(): HasOne
    {
        return $this->hasOne(UserRole::class, 'role_id', 'role_id');
    }

    public function RecentTechTips(): HasManyThrough
    {
        return $this->hasManyThrough(
            TechTip::class,
            UserTechTipRecent::class,
            'user_id',
            'tip_id'
        )->latest('user_tech_tip_recents.created_at')
            ->select(['slug', 'subject'])
            ->limit(10);
    }

    public function RecentCustomers(): HasManyThrough
    {
        return $this->hasManyThrough(
            Customer::class,
            UserCustomerRecent::class,
            'user_id',
            'cust_id',
        )->latest('user_customer_recents.created_at')
            ->select(['slug', 'name'])
            ->limit(10);
    }

    public function TechTipBookmarks(): BelongsToMany
    {
        return $this->belongsToMany(
            TechTip::class,
            'user_tech_tip_bookmarks',
            'user_id',
            'tip_id'
        )->select(['slug', 'subject']);
    }

    public function CustomerBookmarks(): BelongsToMany
    {
        return $this->belongsToMany(
            Customer::class,
            'user_customer_bookmarks',
            'user_id',
            'cust_id',
        )->select(['slug', 'name']);
    }

    public function FileLinks(): HasMany
    {
        return $this->hasMany(FileLink::class, 'user_id', 'user_id');
    }

    /*
    |---------------------------------------------------------------------------
    | Additional Model Methods
    |---------------------------------------------------------------------------
    */

    /**
     * Check a user's Setting value based on the name field.
     */
    public function checkUserSetting(string $settingName): bool
    {
        $settingTypeId = UserSettingType::where('name', $settingName)
            ->first()
            ->setting_type_id;

        $setting = $this->UserSettings
            ->where('setting_type_id', $settingTypeId)
            ->first();

        if (is_null($setting)) {
            return false;
        }

        return $setting->value;
    }

    /**
     * Determine the new expire date for an updated password
     */
    public function getNewExpireTime(?bool $immediate = false): ?Carbon
    {
        if ($immediate) {
            return Carbon::yesterday();
        }

        return (int) config('auth.passwords.settings.expire') > 0
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

    /**
     * Unhide fields to allow User Administration
     */
    public function getAdminLoad(): User
    {
        return $this->makeVisible([
            'role_id',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
    }

    /**
     * Function to get login history for the last xx days
     */
    public function getLoginHistory($days = 365)
    {
        return $this->UserLogins
            ->where('created_at', '>', now()->subDays($days));
    }

    /**
     * Get the date and time of last login
     */
    public function getLastLogin()
    {
        return $this->UserLogins()
            ->orderBy('created_at', 'desc')
            ->first();
    }
}

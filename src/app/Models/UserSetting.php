<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserSetting extends Model
{
    /** @var string */
    protected $primaryKey = 'setting_id';

    /** @var array<int, string> */
    protected $guarded = ['setting_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = [
        'setting_id',
        'user_id',
        'created_at',
        'updated_at',
        'UserSettingType',
    ];

    public function casts(): array
    {
        return [
            'value' => 'boolean',
        ];
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    /** @var array<string, string> */
    protected $appends = ['name'];

    public function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->UserSettingType->name,
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function UserSettingType(): HasOne
    {
        return $this->hasOne(
            UserSettingType::class,
            'setting_type_id',
            'setting_type_id'
        );
    }
}

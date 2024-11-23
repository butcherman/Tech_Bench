<?php

namespace App\Models;

use App\Observers\CustomerContactPhoneObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ObservedBy([CustomerContactPhoneObserver::class])]
class CustomerContactPhone extends Model
{
    use HasFactory;

    /** @var array<int, string> */
    protected $guarded = ['id', 'updated_at', 'created_at'];

    /** @var array<int, string> */
    protected $hidden = ['created_at', 'updated_at', 'phone_type_id'];

    /** @var array<int, string> */
    protected $with = ['PhoneNumberType'];

    /** @var array<int, string> */
    protected $appends = ['formatted'];

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function formatted(): Attribute
    {
        // Get a readable number in a familiar NA format
        return Attribute::make(
            get: fn () => preg_replace(
                '~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~',
                '($1) $2-$3',
                $this->phone_number
            )
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function PhoneNumberType(): HasOne
    {
        return $this->hasOne(
            PhoneNumberType::class,
            'phone_type_id',
            'phone_type_id'
        );
    }
}

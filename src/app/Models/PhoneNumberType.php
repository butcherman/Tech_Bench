<?php

namespace App\Models;

use App\Observers\PhoneNumberTypeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([PhoneNumberTypeObserver::class])]
class PhoneNumberType extends Model
{
    protected $primaryKey = 'phone_type_id';

    protected $guarded = ['phone_type_id', 'created_at', 'updated_at'];

    protected $hidden = ['phone_type_id', 'created_at', 'updated_at'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneNumberType extends Model
{
    /** @var string */
    protected $primaryKey = 'phone_type_id';

    /** @var array<int, string> */
    protected $guarded = ['phone_type_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['phone_type_id', 'created_at', 'updated_at'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneNumberType extends Model
{
    use HasFactory;

    protected $primaryKey = 'phone_type_id';
    protected $guarded    = ['phone_type_id', 'created_at', 'updated_at'];
    protected $hidden     = ['phone_type_id', 'created_at', 'updated_at'];
}

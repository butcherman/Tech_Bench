<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerContactPhone extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'updated_at', 'created_at'];
    protected $hidden  = ['created_at', 'updated_at', 'phone_type_id'];
    protected $appends = ['formatted'];

    /*
    * Mobile, Work, Home, etc
    */
    public function PhoneNumberType()
    {
        return $this->hasOne(PhoneNumberType::class, 'phone_type_id', 'phone_type_id');
    }

    /*
    *   Get a readable number in a familiar NA Format
    */
    public function getFormattedAttribute()
    {
        return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '($1) $2-$3', $this->phone_number);
    }
}
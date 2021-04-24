<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerContactPhone extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'updated_at', 'created_at'];
    protected $hidden  = ['created_at', 'updated_at', 'phone_type_id'];
    protected $appends = ['formatted'];

    public function PhoneNumberType()
    {
        return $this->hasOne(PhoneNumberType::class, 'phone_type_id', 'phone_type_id');
    }

    public function getFormattedAttribute()
    {
        return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '($1) $2-$3', $this->phone_number);
    }
}

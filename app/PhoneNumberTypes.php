<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhoneNumberTypes extends Model
{
    protected $primaryKey = 'phone_type_id';
    protected $fillable = ['phone_type_id', 'description', 'icon_class'];

    // public function CustomerContactPhones()
    // {
    //     return $this->belongsTo('App\CustomerContactPhones', 'phone_type_id');
    // }

    //  Format a 10 digit phone number into a readable format
    public static function readablePhoneNumber($number)
    {
        return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '($1) $2-$3', $number);
    }

    //  Change a formatted phone number into 10 direct digits for placement into a database
    public static function cleanPhoneNumber($number)
    {
        return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '$1$2$3', $number);
    }
}

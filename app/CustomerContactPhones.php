<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerContactPhones extends Model
{
    protected $fillable = ['cont_id', 'phone_type_id', 'phone_number', 'extension'];

    // public function CustomerContacts()
    // {
    //     return $this->hasMany('App\CustomerContacts', 'cont_id', 'cont_id');
    // }

    // public function PhoneNumberTypes()
    // {
    //     return $this->hasMany('App\PhoneNumberTypes', 'phone_type_id', 'phone_type_id');
    // }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhoneNumberType extends Model
{
    protected $primaryKey = 'phone_type_id';
    protected $fillable = ['phone_type_id', 'description', 'icon_class'];
    
    public function CustomerContactPhones()
    {
        return $this->belongsTo('App\CustomerContactPhones', 'phone_type_id');
    }
}

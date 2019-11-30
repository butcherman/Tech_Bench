<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerContactPhones extends Model
{
    protected $fillable = ['cont_id', 'phone_type_id', 'phone_number', 'extension'];
    protected $appends = ['type_icon', 'type_name', 'readable'];

    public function getTypeIconAttribute()
    {
        return PhoneNumberTypes::find($this->phone_type_id)->icon_class;
    }

    public function getTypeNameAttribute()
    {
        return PhoneNumberTypes::find($this->phone_type_id)->description;
    }

    public function getReadableAttribute()
    {
        return PhoneNumberTypes::readablePhoneNumber($this->phone_number);
    }

    public function PhoneNumberTypes()
    {
        return $this->hasOne('App\PhoneNumberTypes', 'phone_type_id', 'phone_type_id');
    }
}

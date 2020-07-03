<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerContactPhones extends Model
{
    protected $fillable = ['cont_id', 'phone_type_id', 'phone_number', 'extension'];
    protected $appends  = ['type_icon', 'type_name', 'readable'];
    protected $hidden   = ['created_at', 'updated_at'];

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
        return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '($1) $2-$3', $this->phone_number);
    }
}

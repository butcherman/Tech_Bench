<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerSystemData extends Model
{
    protected $fillable = ['cust_sys_id', 'field_id', 'value'];
    protected $hidden   = ['id', 'created_at', 'updated_at', 'SystemDataFields'];
    protected $appends  = ['data_field_name'];

    public function SystemDataFields()
    {
        return $this->belongsTo('App\SystemDataFields', 'field_id', 'field_id');
    }

    public function getDataFieldNameAttribute()
    {
        return $this->SystemDataFields->data_field_name;
    }
}

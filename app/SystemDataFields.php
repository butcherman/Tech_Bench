<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemDataFields extends Model
{
    protected $primaryKey = 'field_id';
    protected $fillable = ['sys_id', 'data_type_id', 'order'];
    protected $hidden = ['data_type_id', 'order', 'created_at', 'updated_at'];

    // public function systemCustDataTypes()
    // {
    //     return $this->hasMany('App\systemCustDataTypes', 'data_type_id', 'data_type_id');
    // }

    // public function systemTypes()
    // {
    //     return $this->hasMany('App\SystemTypes', 'sys_id', 'sys_id');
    // }

    // public function CustomerSystemFields()
    // {
    //     return $this->belongsTo('App\CustomerSystemFields', 'field_id');
    // }

    public function SystemDataFieldTypes()
    {
        return $this->belongsTo('App\SystemDataFieldTypes', 'data_type_id', 'data_type_id');
    }


}

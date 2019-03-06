<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemCustDataFields extends Model
{
    protected $primaryKey = 'field_id';
    protected $fillable = ['sys_id', 'data_type_id', 'order'];
    
    public function systemCustDataTypes()
    {
        return $this->hasMany('App\systemCustDataTypes', 'data_type_id', 'data_type_id');
    }
    
    public function systemTypes()
    {
        return $this->hasMany('App\SystemTypes', 'sys_id', 'sys_id');
    }
    
    public function CustomerSystemFields()
    {
        return $this->belongsTo('App\CustomerSystemFields', 'field_id');
    }
}

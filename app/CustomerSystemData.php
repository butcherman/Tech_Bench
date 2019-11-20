<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerSystemData extends Model
{
    protected $fillable = ['cust_sys_id', 'field_id', 'value'];

    // public function CustomerSystems()
    // {
    //     return $this->belongsTo('App\CustomerSystems', 'cust_sys_id', 'cust_sys_id');
    // }

    // public function SystemDataFields()
    // {
    //     return $this->hasOne('App\SystemDataFields', 'field_id', 'field_id');
    // }

    public function SystemDataFields()
    {
        return $this->belongsTo('App\SystemDataFields', 'field_id', 'field_id');
    }
}

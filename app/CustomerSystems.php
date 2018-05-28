<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerSystems extends Model
{
    protected $primaryKey = 'cust_sys_id';
    protected $fillable = ['cust_id', 'sys_id'];
    
    public function Customers()
    {
        return $this->hasMany('App\customers', 'cust_id', 'cust_id');
    }
    
    public function SystemTypes()
    {
        return $this->hasMany('App\SystemTypes', 'sys_id', 'sys_id');
    }
    
    public function CustomerSystemFields()
    {
        return $this->belongsTo('App\CustomerSystemFields', 'cust_sys_id');
    }
}

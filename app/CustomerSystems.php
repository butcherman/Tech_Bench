<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerSystems extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'cust_sys_id';
    protected $fillable   = ['cust_id', 'sys_id', 'shared'];
    protected $hidden     = ['created_at', 'updated_at', 'deleted_at', 'SystemTypes'];
    protected $appends    = ['sys_name'];

    public function SystemTypes()
    {
        return $this->hasOne('App\SystemTypes', 'sys_id', 'sys_id');
    }

    public function CustomerSystemData()
    {
        return $this->hasMany('App\CustomerSystemData', 'cust_sys_id');
    }

    public function getSysNameAttribute()
    {
        return $this->SystemTypes->name;
    }
}

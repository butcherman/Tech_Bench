<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerSystemData extends Model
{
    protected $fillable = ['cust_sys_id', 'field_id', 'value'];
}

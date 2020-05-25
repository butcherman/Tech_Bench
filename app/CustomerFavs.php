<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerFavs extends Model
{
    protected $fillable = ['user_id', 'cust_id'];
    protected $appends  = ['name'];
    protected $hidden   = ['created_at', 'updated_at', 'user_id', 'id'];

    public function Customers()
    {
        return $this->belongsTo('App\Customers', 'cust_id', 'cust_id');
    }

    public function getNameAttribute()
    {
        return $this->Customers->name;
    }
}

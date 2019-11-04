<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerFavs extends Model
{
    protected $fillable = ['user_id', 'cust_id'];

    // public function users()
    // {
    //     return $this->hasMany('App\users', 'user_id', 'user_id');
    // }

    // public function customers()
    // {
    //     return $this->hasMany('App\customers', 'cust_id', 'cust_id');
    // }
}

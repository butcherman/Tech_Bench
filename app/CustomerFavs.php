<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerFavs extends Model
{
    protected $fillable = ['user_id', 'cust_id'];
}

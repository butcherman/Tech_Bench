<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerContacts extends Model
{
    protected $primaryKey = 'cont_id';
    protected $fillable = ['cust_id', 'name', 'email', 'shared'];

    public function CustomerContactPhones()
    {
        return $this->hasMany('App\CustomerContactPhones', 'cont_id');
    }
}

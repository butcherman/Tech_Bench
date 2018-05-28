<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerContacts extends Model
{
    protected $primaryKey = 'cont_id';
    protected $fillable = ['cust_id', 'name', 'email'];
    
    public function CustomerContactPhones()
    {
        return $this->belongsTo('App\CustomerContactPhones', 'cont_id');
    }
    
    public function CustomerContactsView()
    {
        return $this->hasMany('App\CustomerContactsView', 'cont_id', 'cont_id');
    }
}

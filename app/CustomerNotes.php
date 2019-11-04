<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerNotes extends Model
{
    protected $primaryKey = 'note_id';
    protected $fillable = ['cust_id', 'user_id', 'urgent', 'subject', 'description'];

    // public function users()
    // {
    //     return $this->hasMany('App\users', 'user_id', 'user_id');
    // }

    // public function customers()
    // {
    //     return $this->hasMany('App\customers', 'cust_id', 'cust_id');
    // }
}

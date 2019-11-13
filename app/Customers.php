<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customers extends Model
{
    protected $primaryKey = 'cust_id';
    protected $fillable = ['cust_id', 'name', 'dba_name', 'address', 'city', 'state', 'zip', 'active'];
    protected $hidden = ['created_at', 'deleted_at', 'updated_at'];

    public function CustomerSystems()
    {
        return $this->hasMany('App\CustomerSystems', 'cust_id', 'cust_id');
    }






    // public function customerFavs()
    // {
    //     return $this->belongsTo('App\CustomerFavs', 'cust_id', 'cust_id');
    // }

    // public function customerNotes()
    // {
    //     return $this->belongsTo('App\CustomerNotes', 'cust_id', 'cust_id');
    // }

    // public function fileLinks()
    // {
    //     return $this->belongsTo('App\FileLinks', 'cust_id', 'cust_id');
    // }
}

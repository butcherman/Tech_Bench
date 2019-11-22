<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customers extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'cust_id';
    protected $fillable = ['cust_id', 'name', 'dba_name', 'address', 'city', 'state', 'zip', 'active'];
    protected $hidden = ['created_at', 'deleted_at', 'updated_at'];

    public function CustomerSystems()
    {
        return $this->hasMany('App\CustomerSystems', 'cust_id', 'cust_id');
    }
}

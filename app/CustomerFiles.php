<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerFiles extends Model
{
    protected $primaryKey = 'cust_file_id';
    protected $fillable = ['file_id', 'cust_id', 'file_type_id', 'user_id', 'name'];

    // public function CustomerFileTypes()
    // {
    //     return $this->hasMany('App\CustomerFileTypes', 'file_type_id', 'file_type_id');
    // }
}

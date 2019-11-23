<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerFiles extends Model
{
    protected $primaryKey = 'cust_file_id';
    protected $fillable = ['file_id', 'cust_id', 'file_type_id', 'user_id', 'name'];
    protected $hidden = ['file_type_id', 'cust_id', 'user_id'];
    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y'
    ];

    public function CustomerFileTypes()
    {
        return $this->hasOne('App\CustomerFileTypes', 'file_type_id', 'file_type_id');
    }

    public function Files()
    {
        return $this->hasOne('App\Files', 'file_id', 'file_id');
    }

    public function User()
    {
        return $this->hasOne('App\User', 'user_id', 'user_id');
    }
}

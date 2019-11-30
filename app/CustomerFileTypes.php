<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerFileTypes extends Model
{
    protected $primaryKey = 'file_type_id';
    protected $fillable = ['description'];
    protected $hidden = ['created_at', 'updated_at'];
}

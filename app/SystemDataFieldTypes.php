<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemDataFieldTypes extends Model
{
    protected $primaryKey = 'data_type_id';
    protected $fillable   = ['name'];
    protected $hidden     = ['created_at', 'updated_at'];
}

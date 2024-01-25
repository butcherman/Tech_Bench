<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCode extends Model
{
    protected $primaryKey = 'id';

    public $guarded = ['id', 'created_at', 'updated_at'];
}

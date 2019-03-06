<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInitialize extends Model
{
    protected $fillable = ['username', 'token'];
}

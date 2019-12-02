<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRoleType extends Model
{
    //
    protected $primaryKey = 'role_id';
    protected $fillable = ['description'];
}

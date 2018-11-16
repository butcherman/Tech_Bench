<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    protected $primaryKey = 'role_id';
    protected $fillable = ['role_id', 'name', 'description'];
    
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_role', 'user_id', 'role_id');
    }
}

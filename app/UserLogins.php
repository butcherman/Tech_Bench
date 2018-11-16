<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLogins extends Model
{
    protected $fillable = ['user_id', 'ip_address'];
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'user_id');
    }
}

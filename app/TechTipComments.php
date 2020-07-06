<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TechTipComments extends Model
{
    protected $primaryKey = 'comment_id';
    protected $fillable   = ['tip_id', 'user_id', 'comment'];
    protected $hidden     = ['user_id'];
    protected $appends    = ['edit'];
    protected $casts      = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
    ];

    public function User()
    {
        return $this->hasOne('App\User', 'user_id', 'user_id');
    }

    public function getEditAttribute()
    {
        if(Auth::check())
        {
            return Auth::user()->user_id == $this->user_id ? true : false;
        }

        return false;
    }
}

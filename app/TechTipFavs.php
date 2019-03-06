<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TechTipFavs extends Model
{
    protected $fillable = ['user_id', 'tip_id'];
    
    public function users()
    {
        return $this->hasMany('App\Users', 'user_id', 'user_id');
    }
    
    public function techTips()
    {
        return $this->hasMany('App\TechTips', 'tip_id', 'tip_id');
    }
}

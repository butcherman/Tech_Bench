<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TechTips extends Model
{
    protected $primaryKey = 'tip_id';
    protected $fillable = ['user_id', 'subject', 'documentation', 'description', 'created_at'];  // ToDo:  Remove Created_at - future build
    
    public function user()
    {
        return $this->hasOne('App\User', 'user_id', 'user_id');
    }
    
    public function techTipComments()
    {
        return $this->belongsTo('App\TechTipComments', 'tip_id', 'tip_id');
    }
    
    public function techTipFiles()
    {
        return $this->belongsTo('App\TechTipFiles', 'tip_id', 'tip_id');
    }
    
    public function techTipSystems()
    {
        return $this->hasMany('App\TechTipSystems', 'tip_id', 'tip_id');
    }
}

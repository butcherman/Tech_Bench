<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TechTips extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'tip_id';
    protected $fillable   = ['user_id', 'subject', 'tip_type_id', 'description', 'created_at'];  // ToDo:  Remove Created_at - future build
    protected $hidden     = ['public', 'user_id', 'tip_type_id'];
    protected $casts      = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
    ];

    public function systemTypes()
    {
        return $this->hasManyThrough('App\SystemTypes', 'App\TechTipSystems',  'tip_id', 'sys_id', 'tip_id', 'sys_id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'user_id', 'user_id');
    }

    public function techTipTypes()
    {
        return $this->hasOne('App\TechTipTypes', 'tip_type_id', 'tip_type_id');
    }

    public function techTipSystems()
    {
        return $this->hasMany('App\TechTipSystems', 'tip_id', 'tip_id');
    }
}

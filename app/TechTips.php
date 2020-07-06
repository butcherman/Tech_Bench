<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class TechTips extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'tip_id';
    protected $fillable   = ['user_id', 'updated_id', 'subject', 'tip_type_id', 'description', 'public', 'sticky'];
    protected $hidden     = ['public', 'user_id', 'updated_id', 'deleted_at'];
    protected $appends    = ['summary'];
    protected $casts      = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'sticky'     => 'boolean',
    ];

    public function getSummaryAttribute()
    {
        return Str::words($this->description, 50);
    }

    public function systemTypes()
    {
        return $this->hasManyThrough('App\SystemTypes', 'App\TechTipSystems', 'tip_id', 'sys_id', 'tip_id', 'sys_id');
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

    public function techTipFiles()
    {
        return $this->hasMany('App\TechTipFiles', 'tip_id', 'tip_id');
    }
}

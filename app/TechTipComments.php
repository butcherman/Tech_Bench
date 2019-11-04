<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TechTipComments extends Model
{
    protected $primaryKey = 'comment_id';
    protected $fillable = ['tip_id', 'user_id', 'comment'];

    // public function techTips()
    // {
    //     return $this->belongsTo('App\TechTips');
    // }

    // public function users()
    // {
    //     return $this->hasMany('App\Users');
    // }
}

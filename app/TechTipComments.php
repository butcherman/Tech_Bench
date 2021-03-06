<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TechTipComments extends Model
{
    protected $primaryKey = 'comment_id';
    protected $fillable   = ['tip_id', 'user_id', 'comment'];
    protected $hidden     = ['user_id'];
    protected $casts      = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
    ];

    public function User()
    {
        return $this->hasOne('App\User', 'user_id', 'user_id');
    }
}

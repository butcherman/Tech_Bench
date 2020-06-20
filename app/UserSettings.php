<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    protected $primaryKey = 'user_id';
    protected $fillable   = ['user_id', 'em_tech_tip', 'em_file_link', 'em_notification', 'auto_del_link'];
    protected $hidden     = ['created_at', 'updated_at', 'id', 'user_id'];

    public function User()
    {
        return $this->hasOne('App\User', 'user_id', 'user_id')->withTrashed();
    }
}


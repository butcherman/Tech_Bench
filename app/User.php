<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'first_name', 'last_name', 'email', 'password', 'password_expires', 'active', 'is_installer'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'is_installer'
    ];
    
    //  Database primary key
    protected $primaryKey = 'user_id';
    
    public function UserLogins()
    {
        return $this->hasMany('App\UserLogins', 'user_id', 'user_id');
    }
    
    public function systemFiles()
    {
        return $this->hasMany('App\SystemFiles', 'user_id', 'user_id');
    }
    
    public function customerFavs()
    {
        return $this->belongsTo('App\CustomerFavs', 'user_id', 'user_id');
    }
    
    public function customerNotes()
    {
        return $this->belongsTo('App\CustomerNotes', 'user_id', 'user_id');
    }
    
    public function techTips()
    {
        return $this->belongsTo('App\TechTips', 'user_id', 'user_id');
    }
    
    public function techTipComments()
    {
        return $this->belongsTo('App\TechTipComments', 'user_id', 'user_id');
    }
    
    public function fileLinks()
    {
        return $this->hasMany('App\FileLinks', 'user_id', 'user_id');
    }
}

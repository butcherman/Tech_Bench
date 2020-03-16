<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['role_id', 'username', 'first_name', 'last_name', 'email', 'password', 'password_expires', 'active'];
    protected $hidden = ['password', 'remember_token', 'is_installer', 'active', 'created_at', 'password_expires', 'updated_at', 'user_id', 'username'];
    //  Database primary key
    protected $primaryKey = 'user_id';

    protected $appends = [ 'full_name' ];
    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y'
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function UserLogins()
    {
        return $this->hasMany('App\UserLogins', 'user_id', 'user_id');
    }

    public function LastUserLogin()
    {
        return $this->hasOne('App\UserLogins', 'user_id', 'user_id')->latest();
    }

    public function FileLinks()
    {
        return $this->hasMany('App\FileLinks', 'user_id', 'user_id');
    }

    public function UserSettings()
    {
        return $this->hasOne('App\UserSettings', 'user_id', 'user_id');
    }
}

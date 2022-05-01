<?php

namespace App\Models;

use App\Traits\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use CanResetPassword;

    protected $primaryKey = 'user_id';
    protected $guarded    = ['created_at', 'updated_at'];
    protected $hidden     = ['role_id', 'password', 'remember_token', 'deleted_at', 'created_at', 'password_expires', 'updated_at', 'user_id'];
    protected $appends    = ['full_name', 'initials'];
    protected $casts      = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y'
    ];

    /*
    *   Users First and Last name combined
    */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /*
    *   User Initials
    */
    public function getInitialsAttribute()
    {
        return "{$this->first_name[0]}{$this->last_name[0]}";
    }

    /*
    *   Each user is assigned to a role that determines what permissions they are allowed
    */
    public function UserRoles()
    {
        return $this->hasOne('App\Models\UserRoles', 'role_id', 'role_id');
    }

    /*
    *   Each user has their own individual settings
    */
    public function UserSetting()
    {
        return $this->hasMany(UserSetting::class, 'user_id', 'user_id');
    }

    /**
     * List of login times and locations for each user
     */
    public function UserLogins()
    {
        return $this->hasMany(UserLogins::class, 'user_id', 'user_id');
    }

    // public function TechTipBookmark()
    // {
    //     return $this->hasOne(TechTipBookmark::class, 'user_id', 'user_id');
    // }
}

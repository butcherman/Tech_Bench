<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

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
}

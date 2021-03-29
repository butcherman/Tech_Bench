<?php

namespace App\Models;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements CanResetPassword
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'user_id';
    protected $guarded    = ['created_at', 'updated_at'];
    protected $hidden     = ['role_id', 'password', 'remember_token', 'deleted_at', 'created_at', 'password_expires', 'updated_at', 'user_id', 'username'];
    protected $appends    = ['full_name', 'initials'];
    protected $casts      = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y'
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getInitialsAttribute()
    {
        return "{$this->first_name[0]}{$this->last_name[0]}";
    }
}

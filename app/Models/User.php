<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'user_id';
    protected $guarded = ['created_at', 'updated_at'];
    protected $hidden = ['role_id', 'password', 'remember_token', 'deleted_at', 'created_at', 'password_expires', 'updated_at', 'user_id', 'username'];
}

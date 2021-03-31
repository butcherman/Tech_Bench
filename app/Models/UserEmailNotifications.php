<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEmailNotifications extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';
    protected $guarded    = ['created_at', 'updated_at'];
    protected $hidden     = ['user_id', 'created_at', 'updated_at'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInitialize extends Model
{
    use HasFactory;

    protected $fillable = ['username', 'token'];

    protected $hidden = ['id', 'created_at', 'updated_at'];
}

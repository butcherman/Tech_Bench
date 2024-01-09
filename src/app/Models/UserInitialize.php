<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInitialize extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    protected $hidden = ['id', 'created_at', 'updated_at'];
}

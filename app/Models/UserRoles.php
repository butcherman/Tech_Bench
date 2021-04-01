<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    use HasFactory;

    protected $primaryKey = 'role_id';
    protected $guarded    = ['role_id', 'allow_edit', 'created_at', 'updated_at'];
    protected $hidden     = ['allow_edit', 'created_at', 'updated_at'];
}

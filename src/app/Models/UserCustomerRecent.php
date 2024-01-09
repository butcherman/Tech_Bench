<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCustomerRecent extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'updated_at', 'created_at'];

    protected $hidden = ['id', 'user_id', 'created_at', 'Customer'];
}

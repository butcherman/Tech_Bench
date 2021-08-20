<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCustomerBookmark extends Model
{
    use HasFactory;

    protected $guarded      = ['updated_at', 'created_at'];
    protected $hidden       = ['updated_at', 'created_at'];
}

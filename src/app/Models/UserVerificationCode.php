<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVerificationCode extends Model
{
    public $guarded = ['id', 'created_at', 'updated_at'];
}

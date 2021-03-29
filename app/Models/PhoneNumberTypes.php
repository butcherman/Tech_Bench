<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneNumberTypes extends Model
{
    use HasFactory;

    protected $primaryKey = 'phone_type_id';
    protected $fillable   = ['phone_type_id', 'description', 'icon_class'];
    protected $hidden     = ['created_at', 'updated_at'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerContactPhone extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'updated_at', 'created_at'];

    protected $hidden = ['created_at', 'updated_at', 'phone_type_id'];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSettings extends Model
{
    protected $primaryKey = 'id';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['id', 'created_at', 'updated_at'];
}

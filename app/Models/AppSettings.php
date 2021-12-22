<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppSettings extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $guarded    = ['id', 'created_at', 'updated_at'];
    protected $hidden     = ['id', 'created_at', 'updated_at'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataFieldType extends Model
{
    use HasFactory;

    protected $primaryKey = 'type_id';
    protected $fillable   = ['name', 'hidden'];
    protected $hidden     = ['updated_at', 'created_at'];
}

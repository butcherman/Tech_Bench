<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerFileType extends Model
{
    use HasFactory;

    protected $primaryKey = 'file_type_id';
    protected $guarded    = ['file_type_id', 'created_at', 'updated_at'];
    protected $hidden     = ['created_at', 'updated_at', 'file_type_id'];
}

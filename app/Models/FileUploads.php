<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUploads extends Model
{
    use HasFactory;

    protected $primaryKey = 'file_id';
    protected $guarded    = ['file_id', 'created_at', 'updated_at'];
    protected $hidden     = ['disk', 'created_at', 'folder', 'updated_at', 'public'];
}

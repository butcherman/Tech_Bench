<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileLinkFile extends Model
{
    use HasFactory;

    protected $primaryKey = 'link_file_id';
}

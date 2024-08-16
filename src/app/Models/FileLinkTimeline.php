<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileLinkTimeline extends Model
{
    use HasFactory;

    protected $primaryKey = 'timeline_id';

    protected $guarded = ['timeline_id', 'created_at', 'updated_at'];
}

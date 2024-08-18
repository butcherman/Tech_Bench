<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileLinkTimeline extends Model
{
    use HasFactory;

    protected $primaryKey = 'timeline_id';

    protected $guarded = ['timeline_id', 'updated_at'];

    protected $hidden = ['updated_at'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y h:i A',
        'updated_at' => 'datetime:M d, Y',
    ];
}

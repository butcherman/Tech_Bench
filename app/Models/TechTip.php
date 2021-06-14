<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechTip extends Model
{
    use HasFactory;

    protected $primaryKey = 'tip_id';
    protected $guarded    = ['tip_id', 'created_at', 'updated_at'];
    protected $hidden     = [];
}

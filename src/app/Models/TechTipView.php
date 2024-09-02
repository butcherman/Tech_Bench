<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechTipView extends Model
{
    use HasFactory;

    protected $primaryKey = 'tip_id';

    protected $guarded = ['created_at', 'updated_at'];

    protected $hidden = ['created_at', 'updated_at'];
}

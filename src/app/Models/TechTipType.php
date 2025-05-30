<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechTipType extends Model
{
    protected $primaryKey = 'tip_type_id';

    protected $guarded = ['tip_type_id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at', 'updated_at'];
}

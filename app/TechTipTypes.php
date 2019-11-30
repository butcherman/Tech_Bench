<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TechTipTypes extends Model
{
    protected $primaryKey = 'tip_type_id';
    protected $fillable = ['description'];
    protected $hidden = ['created_at', 'updated_at'];
}

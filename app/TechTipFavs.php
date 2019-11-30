<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TechTipFavs extends Model
{
    protected $fillable = ['user_id', 'tip_id'];
}

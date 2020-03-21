<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TechTipSystems extends Model
{
    protected $primaryKey = 'tip_tag_id';
    protected $fillable   = ['tip_id', 'sys_id'];
}

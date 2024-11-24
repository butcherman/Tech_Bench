<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechTipView extends Model
{
    use HasFactory;

    /** @var string */
    protected $primaryKey = 'tip_id';

    /** @var array<int, string> */
    protected $guarded = ['created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['created_at', 'updated_at'];
}

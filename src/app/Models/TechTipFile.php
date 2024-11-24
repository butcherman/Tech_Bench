<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechTipFile extends Model
{
    use HasFactory;

    /** @var string */
    protected $primaryKey = 'tip_file_id';

    /** @var array<int, string> */
    protected $guarded = ['tip_file_it', 'created_at', 'updated_at'];
}

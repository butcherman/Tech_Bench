<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TechTip extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'tip_id';

    protected $guarded = ['tip_id', 'created_at', 'updated_at'];

    protected $hidden = ['deleted_at', 'tip_type_id', 'updated_id', 'user_id'];

    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
        'sticky' => 'boolean',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechTip extends Model
{
    /** @var string */
    protected $primaryKey = 'tip_id';

    /** @var array<int, string> */
    protected $guarded = ['tip_id', 'updated_at', 'created_at'];

    /** @var array<int, string> */
    protected $hidden = ['deleted_at', 'tip_type_id', 'Bookmarks'];

    /** @var array<string, string> */
    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
        'sticky' => 'boolean',
        'public' => 'boolean',
    ];
}

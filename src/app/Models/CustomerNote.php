<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerNote extends Model
{
    use SoftDeletes;

    /** @var string */
    protected $primaryKey = 'note_id';

    /** @var array<int, string> */
    protected $guarded = ['note_id', 'updated_at', 'created_at'];

    /** @var array<string, string> */
    protected $casts = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
        'deleted_at' => 'datetime:M d, Y',
        'urgent' => 'boolean',
    ];
}

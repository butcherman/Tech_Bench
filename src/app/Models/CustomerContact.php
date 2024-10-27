<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerContact extends Model
{
    use SoftDeletes;

    /** @var string */
    protected $primaryKey = 'cont_id';

    /** @var array<int, string> */
    protected $guarded = ['cont_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    /** @var array<string, string> */
    protected $casts = [
        'deleted_at' => 'datetime:M d, Y',
        'local' => 'boolean',
        'decision_maker' => 'boolean',
    ];
}

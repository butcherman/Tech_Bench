<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerContact extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'cont_id';

    protected $guarded = ['cont_id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'deleted_at' => 'datetime:M d, Y',
        'local' => 'boolean',
        'decision_maker' => 'boolean',
    ];
}

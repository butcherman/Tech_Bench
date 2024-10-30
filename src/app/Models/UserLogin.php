<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    use HasFactory;

    /** @var string */
    protected $primaryKey = 'id';

    /** @var array<int, string> */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['id', 'updated_at'];

    /** @var array<string, string> */
    protected $casts = [
        'created_at' => 'date',
        'updated_at' => 'date',
    ];
}

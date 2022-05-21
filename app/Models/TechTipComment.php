<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechTipComment extends Model
{
    use HasFactory;

    protected $guarded    = ['id', 'created_at', 'updated_at'];
    protected $hidden     = [];
    protected $casts      = [
        'created_at' => 'datetime:M d, Y',
        'updated_at' => 'datetime:M d, Y',
    ];

    /**
     * Each comment is created by a user
     */
    // public function User()
    // {
    //     return $this->hasOne(User::class, 'user_id', 'user_id');
    // }
}

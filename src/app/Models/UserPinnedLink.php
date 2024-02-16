<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPinnedLink extends Model
{
    use HasFactory;

    protected $primaryKey = 'pin_id';
    protected $guarded = ['pin_id', 'created_at', 'updated_at'];
}

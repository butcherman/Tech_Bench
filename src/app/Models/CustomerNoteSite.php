<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerNoteSite extends Model
{
    use HasFactory;

    protected $guarded = ['updated_at', 'created_at'];
}

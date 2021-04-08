<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'cust_id';
    protected $guarded    = ['updated_at', 'created_at'];
    protected $hidden     = [];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAlert extends Model
{
    use HasFactory;

    protected $primaryKey = 'alert_id';

    protected $guarded = ['alert_id', 'created_at', 'updated_at'];
}

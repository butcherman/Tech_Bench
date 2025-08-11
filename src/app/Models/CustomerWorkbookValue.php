<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerWorkbookValue extends Model
{
    /** @var array<int, string> */
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
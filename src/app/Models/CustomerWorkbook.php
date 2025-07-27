<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerWorkbook extends Model
{
    /** @var string */
    protected $primaryKey = 'wb_id';

    /** @var array<int, string> */
    protected $guarded = ['wb_id', 'created_at', 'updated_at'];
}

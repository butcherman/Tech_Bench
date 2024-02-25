<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataFieldType extends Model
{
    use HasFactory;

    protected $primaryKey = 'type_id';

    protected $guarded = ['type_id', 'created_at', 'updated_at'];

    protected $hidden = ['updated_at', 'created_at'];
}

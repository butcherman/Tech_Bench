<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataField extends Model
{
    use HasFactory;

    protected $primaryKey = 'field_id';

    // TODO - Switch to Guarded
    protected $fillable = ['equip_id', 'type_id', 'order'];

    protected $hidden = ['updated_at', 'created_at'];
}

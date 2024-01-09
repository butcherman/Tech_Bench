<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'cat_id';

    // TODO - Switch to Guarded
    protected $fillable = ['name'];

    protected $hidden = ['updated_at', 'created_at'];
}

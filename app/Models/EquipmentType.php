<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentType extends Model
{
    use HasFactory;

    protected $primaryKey = 'equip_id';
    protected $fillable   = ['cat_id', 'name'];
    protected $hidden     = ['updated_at', 'created_at'];
}

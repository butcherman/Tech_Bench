<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechTipEquipment extends Model
{
    use HasFactory;

    protected $primaryKey = 'tip_equip_id';

    protected $guarded = ['tip_equip_id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at', 'updated_at'];
}

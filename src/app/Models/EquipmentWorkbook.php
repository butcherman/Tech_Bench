<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentWorkbook extends Model
{
    /** @var string */
    protected $primaryKey = 'equip_id';

    /** @var array<int, string> */
    protected $guarded = ['created_at', 'updated_at'];
}
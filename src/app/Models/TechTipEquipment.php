<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TechTipEquipment extends Pivot
{
    use HasFactory;

    /** @var bool */
    public $incrementing = true;

    /** @var string */
    protected $primaryKey = 'tip_equip_id';

    /** @var array<int, string> */
    protected $guarded = ['tip_equip_id', 'created_at', 'updated_at'];

    /** @var array<int, string> */
    protected $hidden = ['created_at', 'updated_at'];
}

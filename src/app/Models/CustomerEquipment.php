<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerEquipment extends Model
{
    use SoftDeletes;

    /** @var string */
    protected $primaryKey = 'cust_equip_id';

    /** @var array<int, string> */
    protected $guarded = ['updated_at', 'created_at'];

    /** @var array<int, string> */
    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
        'EquipmentType',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'deleted_at' => 'datetime:M d, Y',
    ];
}

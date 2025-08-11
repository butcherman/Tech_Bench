<?php

namespace App\Models;

use App\Observers\EquipmentWorkbookObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([EquipmentWorkbookObserver::class])]
class EquipmentWorkbook extends Model
{
    /** @var string */
    protected $primaryKey = 'equip_id';

    /** @var array<int, string> */
    protected $guarded = ['created_at', 'updated_at'];
}
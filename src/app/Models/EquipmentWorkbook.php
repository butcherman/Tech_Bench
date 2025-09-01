<?php

namespace App\Models;

use App\Observers\EquipmentWorkbookObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([EquipmentWorkbookObserver::class])]
class EquipmentWorkbook extends Model
{
    /** @var string */
    protected $primaryKey = 'equip_id';

    /** @var array<int, string> */
    protected $guarded = ['created_at', 'updated_at'];

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    // FIXME: why doesn't this relationship work?
    // public function EquipmentType(): BelongsTo
    // {
    //     return $this->belongsTo(EquipmentType::class, 'equip_id', 'equip_id');
    // }
}

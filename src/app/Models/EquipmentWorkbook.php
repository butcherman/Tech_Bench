<?php

namespace App\Models;

use App\Observers\EquipmentWorkbookObserver;
use Database\Factories\EquipmentWorkbookFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([EquipmentWorkbookObserver::class])]
class EquipmentWorkbook extends Model
{
    /** @use HasFactory<EquipmentWorkbookFactory> */
    use HasFactory;

    /** @var string */
    protected $primaryKey = 'equip_id';

    /** @var bool */
    public $incrementing = false;

    /** @var array<int, string> */
    protected $guarded = ['created_at', 'updated_at'];

    /*
    |---------------------------------------------------------------------------
    | Model Casting
    |---------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'workbook_data' => 'array',
        ];
    }
}

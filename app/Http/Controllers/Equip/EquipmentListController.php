<?php

namespace App\Http\Controllers\Equip;

use App\Models\EquipmentCategory;
use App\Http\Controllers\Controller;

class EquipmentListController extends Controller
{
    /**
     *  Return a JSON list of all equipment categories along with attached equipment types
     */
    public function __invoke()
    {
        return EquipmentCategory::with('EquipmentType.DataFieldType')->get();
    }
}

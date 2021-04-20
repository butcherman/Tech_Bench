<?php

namespace App\Http\Controllers\Equip;

use App\Http\Controllers\Controller;
use App\Models\EquipmentCategory;
use Illuminate\Http\Request;

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

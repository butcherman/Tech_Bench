<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Controllers\Controller;

use App\Models\EquipmentCategory;

class ListEquipmentController extends Controller
{
    /**
     * Get a list of all equipment and categories
     */
    public function __invoke()
    {
        return EquipmentCategory::with('EquipmentType.DataFieldType')->get();
    }
}

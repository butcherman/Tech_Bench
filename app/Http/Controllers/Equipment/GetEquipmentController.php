<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Controllers\Controller;
use App\Models\EquipmentCategory;

class GetEquipmentController extends Controller
{
    /**
     * Return a list of all equipment sorted by category
     */
    public function __invoke()
    {
        return EquipmentCategory::with('EquipmentType.DataFieldType')->get();
    }
}

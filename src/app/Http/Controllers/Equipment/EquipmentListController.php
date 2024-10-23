<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Controllers\Controller;
use App\Models\EquipmentCategory;
use Illuminate\Database\Eloquent\Collection;

class EquipmentListController extends Controller
{
    /**
     * Return a list of Equipment grouped by Category
     */
    public function __invoke(): Collection
    {
        return EquipmentCategory::with('EquipmentType')->get();
    }
}

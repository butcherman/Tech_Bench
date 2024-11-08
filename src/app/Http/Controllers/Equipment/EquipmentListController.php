<?php

namespace App\Http\Controllers\Equipment;

use App\Facades\CacheFacade;
use App\Http\Controllers\Controller;

class EquipmentListController extends Controller
{
    /**
     * Fetch JSON list of all Equipment grouped by Category.
     */
    public function __invoke()
    {
        return CacheFacade::equipmentTypes();
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Facades\CacheFacade;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class GetEquipmentListController extends Controller
{
    /**
     * Fetch JSON list of all Equipment grouped by Category.
     */
    public function __invoke(): JsonResponse
    {
        return response()->json(CacheFacade::equipmentCategories());
    }
}

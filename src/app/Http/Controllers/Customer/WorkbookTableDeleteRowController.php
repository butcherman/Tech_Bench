<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerEquipmentWorkbook;
use App\Services\Customer\CustomerWorkbookService;
use Illuminate\Http\JsonResponse;

class WorkbookTableDeleteRowController extends Controller
{
    public function __construct(protected CustomerWorkbookService $svc) {}

    /**
     * Delete a table row from the database
     */
    public function __invoke(CustomerEquipmentWorkbook $workbook, string $table, string $row): JsonResponse
    {
        $this->svc->deleteTableRow($workbook, $table, $row);

        return response()->json(['success' => true]);
    }
}

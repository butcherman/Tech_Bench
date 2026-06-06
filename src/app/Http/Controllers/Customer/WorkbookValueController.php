<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\WorkbookValueRequest;
use App\Models\CustomerEquipmentWorkbook;
use App\Services\Customer\CustomerWorkbookService;

class WorkbookValueController extends Controller
{
    public function __construct(protected CustomerWorkbookService $svc) {}

    public function __invoke(WorkbookValueRequest $request, CustomerEquipmentWorkbook $workbook)
    {
        $this->svc->saveWorkbookValue($workbook, $request->safe()->collect());

        return response()->json(['success' => true]);
    }
}

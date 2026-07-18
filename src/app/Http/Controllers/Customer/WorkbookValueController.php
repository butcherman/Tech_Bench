<?php

namespace App\Http\Controllers\Customer;

use App\Exceptions\Customer\WorkbookNotPublishedException;
use App\Exceptions\Misc\FeatureDisabledException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\WorkbookValueRequest;
use App\Models\CustomerEquipmentWorkbook;
use App\Services\Customer\CustomerWorkbookService;

class WorkbookValueController extends Controller
{
    public function __construct(protected CustomerWorkbookService $svc)
    {
        throw_unless(
            config('customer.enable_workbooks'),
            FeatureDisabledException::class,
            'Customer Equipment Workbooks'
        );
    }

    public function __invoke(WorkbookValueRequest $request, CustomerEquipmentWorkbook $workbook)
    {
        if (! request()->user()) {
            throw_unless(
                $workbook->published,
                WorkbookNotPublishedException::class
            );
        }

        $this->svc->saveWorkbookValue($workbook, $request->safe()->collect());

        return response()->json(['success' => true]);
    }
}

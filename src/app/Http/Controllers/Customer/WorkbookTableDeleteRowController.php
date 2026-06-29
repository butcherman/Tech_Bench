<?php

namespace App\Http\Controllers\Customer;

use App\Events\Customer\WorkbookTableRowDeletedEvent;
use App\Exceptions\Customer\WorkbookNotPublishedException;
use App\Exceptions\Misc\FeatureDisabledException;
use App\Http\Controllers\Controller;
use App\Models\CustomerEquipmentWorkbook;
use App\Services\Customer\CustomerWorkbookService;
use Illuminate\Http\JsonResponse;

class WorkbookTableDeleteRowController extends Controller
{
    public function __construct(protected CustomerWorkbookService $svc)
    {
        throw_unless(
            config('customer.enable_workbooks'),
            FeatureDisabledException::class,
            'Customer Equipment Workbooks'
        );
    }

    /**
     * Delete a table row from the database
     */
    public function __invoke(CustomerEquipmentWorkbook $workbook, string $table, string $row): JsonResponse
    {
        if (! request()->user()) {
            throw_unless(
                $workbook->published,
                WorkbookNotPublishedException::class
            );
        }

        $this->svc->deleteTableRow($workbook, $table, $row);

        WorkbookTableRowDeletedEvent::dispatch($workbook, $table, $row);

        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers\Customer;

use App\Exceptions\Customer\WorkbookNotPublishedException;
use App\Http\Controllers\Controller;
use App\Models\CustomerEquipmentWorkbook;
use App\Services\Customer\CustomerWorkbookService;
use Inertia\Inertia;

class WorkbookPublicViewController extends Controller
{
    public function __construct(protected CustomerWorkbookService $svc) {}

    /**
     * Show the public version of the workbook.
     */
    public function __invoke(CustomerEquipmentWorkbook $workbook)
    {
        throw_unless($workbook->published, WorkbookNotPublishedException::class);

        return Inertia::render('Customer/Workbook/Public', [
            'customer' => $workbook->Customer->only(['name']),
            'workbook' => $workbook->only(['wb_hash', 'public_workbook']),
            'workbook-values' => Inertia::defer(
                fn () => $this->svc->getAllWorkbookValues($workbook, false),
            ),
        ]);
    }
}

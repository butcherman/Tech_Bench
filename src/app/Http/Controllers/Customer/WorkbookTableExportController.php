<?php

namespace App\Http\Controllers\Customer;

use App\Actions\Misc\CsvWriter;
use App\Exceptions\Customer\WorkbookNotPublishedException;
use App\Exceptions\Misc\FeatureDisabledException;
use App\Http\Controllers\Controller;
use App\Models\CustomerEquipmentWorkbook;
use App\Services\Customer\CustomerWorkbookService;

class WorkbookTableExportController extends Controller
{
    public function __construct(protected CustomerWorkbookService $svc, protected CsvWriter $writer)
    {
        throw_unless(
            config('customer.enable_workbooks'),
            FeatureDisabledException::class,
            'Customer Equipment Workbooks'
        );
    }

    /**
     * Export a specific table's data as a CSV File
     */
    public function __invoke(CustomerEquipmentWorkbook $workbook, string $tableIndex)
    {
        if (! request()->user()) {
            throw_unless(
                $workbook->published,
                WorkbookNotPublishedException::class
            );
        }

        $data = $this->svc->getWorkbookTableValues($workbook, $tableIndex);
        $writer = $this->writer->stream('data_export.csv');

        foreach ($data as $row) {
            $writer->addRow($row);
        }

        $writer->toBrowser();
    }
}

<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerEquipmentWorkbook;
use App\Services\Customer\CustomerWorkbookService;
use Spatie\SimpleExcel\SimpleExcelWriter;

class WorkbookTableExportController extends Controller
{
    public function __construct(protected CustomerWorkbookService $svc) {}

    /**
     * Export a specific table's data as a CSV File
     */
    public function __invoke(CustomerEquipmentWorkbook $workbook, string $tableIndex)
    {
        $data = $this->svc->getWorkbookTableValues($workbook, $tableIndex);
        $writer = SimpleExcelWriter::streamDownload('data_export.csv');

        foreach ($data as $row) {
            $writer->addRow($row);
        }

        $writer->toBrowser();
    }
}

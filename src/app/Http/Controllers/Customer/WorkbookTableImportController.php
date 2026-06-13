<?php

namespace App\Http\Controllers\Customer;

use App\Enums\DiskEnum;
use App\Http\Controllers\FileUploadController;
use App\Http\Requests\Customer\WorkbookTableImportRequest;
use App\Jobs\Customer\ValidateWorkbookImportJob;
use App\Models\CustomerEquipmentWorkbook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class WorkbookTableImportController extends FileUploadController
{
    /**
     * Upload the CSV file to be validated
     */
    public function __invoke(
        WorkbookTableImportRequest $request,
        CustomerEquipmentWorkbook $workbook,
        string $tableIndex
    ): JsonResponse|Response {
        $this->setFileData(DiskEnum::customers, 'table_upload');
        $savedFile = $this->getChunk($request->file('file'), $request);

        if ($savedFile) {
            Cache::tags(['data-import'])->put(session_id(), $savedFile, 86400);
            ValidateWorkbookImportJob::dispatch($workbook, $tableIndex, $savedFile);

            return response()->json(['success' => true, 'file_data', $savedFile]);
        }

        return response()->noContent();
    }
}

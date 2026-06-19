<?php

namespace App\Http\Controllers\Customer;

use App\Enums\DiskEnum;
use App\Http\Controllers\FileUploadController;
use App\Http\Requests\Customer\WorkbookTableImportRequest;
use App\Jobs\Customer\ValidateWorkbookImportJob;
use App\Jobs\Customer\WorkbookImportTableDataJob;
use App\Models\CustomerEquipmentWorkbook;
use App\Services\Customer\CustomerWorkbookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WorkbookTableImportController extends FileUploadController
{
    public function __construct(protected CustomerWorkbookService $svc) {}

    /**
     * Get the values of a specific table
     */
    public function index(CustomerEquipmentWorkbook $workbook, string $tableIndex): JsonResponse
    {
        $values = $this->svc
            ->getWorkbookTableValues($workbook, $tableIndex, true);

        return response()->json($values);
    }

    /**
     * Save a CSV file to be imported into a Customer Workbook
     */
    public function store(
        WorkbookTableImportRequest $request,
        CustomerEquipmentWorkbook $workbook,
        string $tableIndex
    ): JsonResponse|Response {
        $this->setFileData(DiskEnum::customers, 'table_upload');
        $savedFile = $this->getChunk($request->file('file'), $request);

        if ($savedFile) {
            Cache::tags(['data-import'])
                ->put($workbook->wb_hash.$tableIndex, $savedFile, 86400);

            ValidateWorkbookImportJob::dispatchAfterResponse(
                $workbook,
                $tableIndex,
                $savedFile
            );

            return response()
                ->json(['success' => true]);
        }

        return response()->noContent();
    }

    /**
     * Get the Validated results of an upload
     */
    public function show(CustomerEquipmentWorkbook $workbook, string $tableIndex): JsonResponse
    {
        $validated = Cache::get($workbook->wb_hash.$tableIndex);

        return response()->json($validated);
    }

    /**
     * Import the validated file into the database
     */
    public function update(CustomerEquipmentWorkbook $workbook, string $tableIndex): JsonResponse
    {
        $validated = collect(Cache::pull($workbook->wb_hash.$tableIndex));

        if (! $validated) {
            Log::error('Trying to import an invalid CSV file to a workbook table', [
                'workbook' => $workbook->wb_hash,
                'table_index' => $tableIndex,
            ]);

            return response()->json([
                'success' => false,
                'error' => 'File Validation Data Not Found',
            ]);
        }

        $chunks = $validated->chunk(25);

        foreach ($chunks as $key => $chunk) {
            Log::debug(
                'Importing CSV to workbook table - Chunk '.
                $key.
                ' of '.
                $chunks->count()
            );

            WorkbookImportTableDataJob::dispatch(
                $workbook,
                $tableIndex,
                $chunk->toArray(),
                $key,
                $chunks->count()
            );
        }

        return response()->json(['success' => true]);
    }

    public function destroy(CustomerEquipmentWorkbook $workbook, string $tableIndex): JsonResponse
    {
        $this->svc->deleteTableData($workbook, $tableIndex);

        return response()->json(['success' => true]);
    }
}

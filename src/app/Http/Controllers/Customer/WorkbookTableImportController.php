<?php

namespace App\Http\Controllers\Customer;

use App\Enums\DiskEnum;
use App\Http\Controllers\FileUploadController;
use App\Http\Requests\Customer\WorkbookTableImportRequest;
use App\Jobs\Customer\ValidateWorkbookImportJob;
use App\Jobs\Customer\WorkbookImportTableDataJob;
use App\Models\CustomerEquipmentWorkbook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WorkbookTableImportController extends FileUploadController
{
    public function index()
    {
        //
        return 'index';
    }

    public function create()
    {
        //
        return 'create';
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

    public function show(CustomerEquipmentWorkbook $workbook, string $tableIndex): JsonResponse
    {
        $validated = Cache::get($workbook->wb_hash.$tableIndex);

        return response()->json($validated);
    }

    public function edit(string $id)
    {
        //
        return 'edit';
    }

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

    public function destroy(string $id)
    {
        //
        return 'destroy';
    }

    public function restore(string $id)
    {
        //
        return 'restore';
    }

    public function forceDelete(string $id)
    {
        //
        return 'force delete';
    }
}

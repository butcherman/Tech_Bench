<?php

namespace App\Http\Controllers\Customer;

use App\Enums\DiskEnum;
use App\Events\Customer\WorkbookTableLockEvent;
use App\Exceptions\Customer\WorkbookNotPublishedException;
use App\Exceptions\Misc\FeatureDisabledException;
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
    public function __construct(protected CustomerWorkbookService $svc)
    {
        throw_unless(
            config('customer.enable_workbooks'),
            FeatureDisabledException::class,
            'Customer Equipment Workbooks'
        );
    }

    /**
     * Get the values of a specific table
     */
    public function index(CustomerEquipmentWorkbook $workbook, string $tableIndex): JsonResponse
    {
        if (! request()->user()) {
            throw_unless(
                $workbook->published,
                WorkbookNotPublishedException::class
            );
        }

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
        if (! request()->user()) {
            throw_unless(
                $workbook->published,
                WorkbookNotPublishedException::class
            );
        }

        $this->setFileData(DiskEnum::customers, 'table_upload');
        $savedFile = $this->getChunk($request->file('file'), $request);

        if ($savedFile) {
            Cache::tags(['data-import-files'])
                ->put('imported-'.$workbook->wb_hash.$tableIndex, $savedFile, 172800);

            ValidateWorkbookImportJob::dispatchAfterResponse(
                $workbook,
                $tableIndex,
                $savedFile,
                $request->input('publicPage')
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
        if (! request()->user()) {
            throw_unless(
                $workbook->published,
                WorkbookNotPublishedException::class
            );
        }

        $validated = Cache::get($workbook->wb_hash.$tableIndex);

        // TODO - Throw exception if validated data is missing
        return response()->json($validated['data']);
    }

    /**
     * Import the validated file into the database
     */
    public function update(CustomerEquipmentWorkbook $workbook, string $tableIndex): JsonResponse
    {
        if (! request()->user()) {
            throw_unless(
                $workbook->published,
                WorkbookNotPublishedException::class
            );
        }

        $validated = Cache::pull($workbook->wb_hash.$tableIndex);

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

        // Lock the table so it cannot be updated by another user during import.
        broadcast(new WorkbookTableLockEvent($workbook, $tableIndex, true))
            ->toOthers();

        $chunks = collect($validated['data'])->chunk(25);

        foreach ($chunks as $key => $chunk) {
            Log::debug(
                'Importing CSV to workbook table - Chunk '.
                $key.
                ' of '.
                $chunks->count()
            );

            // Process individual chunks so we don't time out
            WorkbookImportTableDataJob::dispatch(
                $workbook,
                $tableIndex,
                $chunk->toArray(),
                $key,
                $chunks->count(),
                $validated['public'],
            );
        }

        return response()->json(['success' => true]);
    }

    /**
     * Delete all data within a table
     */
    public function destroy(CustomerEquipmentWorkbook $workbook, string $tableIndex): JsonResponse
    {
        if (! request()->user()) {
            throw_unless(
                $workbook->published,
                WorkbookNotPublishedException::class
            );
        }

        $this->svc->deleteTableData($workbook, $tableIndex);

        return response()->json(['success' => true]);
    }
}

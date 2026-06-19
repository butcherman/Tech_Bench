<?php

namespace App\Actions\Customer;

use App\Models\CustomerEquipmentWorkbook;
use App\Models\FileUpload;
use App\Services\Customer\CustomerWorkbookService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;
use Spatie\SimpleExcel\SimpleExcelReader;

class ValidateWorkbookImportFile
{
    public function __invoke(
        CustomerEquipmentWorkbook $workbook,
        string $tableIndex,
        FileUpload $file
    ): LazyCollection {
        Log::debug('Starting Validate Table Import File', [
            'workbook_id' => $workbook->wb_id,
            'table_index' => $tableIndex,
            'file_upload_id' => $file->file_id,
        ]);

        $svc = new CustomerWorkbookService;

        $filePath = Storage::disk($file->disk)->path($file->folder);
        $tableHeaders = collect(
            $svc->getTableHeaderData($workbook, $tableIndex)
        );

        $writer = SimpleExcelReader::create(
            $filePath.DIRECTORY_SEPARATOR.$file->hash_name
        )->getRows();

        // Modify each row to add validation data
        $validated = $writer->map(function ($row) use ($tableHeaders) {
            $validatedRow = [];

            // Check if the Column Name is valid
            foreach ($row as $col => $value) {
                $validated = [
                    'valid' => is_null($value) || $value === '' ? true : false,
                    'data_type' => null,
                    'value' => $value,
                    'validation_error' => null,
                ];

                if (! $validated['valid']) {
                    // If the column name is valid, verify the data type is valid
                    $validCol = $tableHeaders->where('name', $col)->first();
                    if ($validCol) {
                        $dataType = $validCol['type'];

                        $validated['data_type'] = $dataType;
                        $validated['valid'] = match ($dataType) {
                            'boolean' => filter_var(
                                $value,
                                FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE
                            ) !== null,
                            'enum' => in_array($value, $validCol['list']),
                            'integer' => is_numeric($value),
                            default => is_string($value),
                        };

                        if (! $validated['valid']) {
                            $validated['validation_error'] = $dataType.' expected';
                        }
                    }

                    if (! $validCol) {
                        $validated['validation_error'] = 'Invalid Column';
                    }
                }

                $validatedRow[$col] = $validated;
            }

            return $validatedRow;
        });

        Log::debug('File Import Validation Completed', $validated->toArray());

        return $validated;
    }
}

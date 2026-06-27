<?php

namespace App\Actions\Customer;

use App\Models\CustomerEquipmentWorkbook;
use App\Models\FileUpload;
use App\Services\Customer\CustomerWorkbookService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;
use Spatie\SimpleExcel\SimpleExcelReader;

class ValidateWorkbookImportFile
{
    /** @var Collection */
    protected $tableHeaders;

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
        $this->tableHeaders = collect(
            $svc->getTableHeaderData($workbook, $tableIndex)
        );

        $writer = SimpleExcelReader::create(
            $filePath.DIRECTORY_SEPARATOR.$file->hash_name
        )->getRows();

        // Modify each row to add validation data
        $validated = $writer->map(function ($row) {
            $validatedRow = [];

            // Check if the Column Name is valid
            foreach ($row as $col => $value) {
                $validatedRow[$col] = $this->validateColumn($col, $value);
            }

            return $validatedRow;
        });

        Log::debug('File Import Validation Completed', $validated->toArray());

        return $validated;
    }

    /**
     * Validate the individual column in each row
     */
    protected function validateColumn(string $col, mixed $value): array
    {
        $columnData = $this->tableHeaders->where('name', $col)->first();

        // If the column does not exist in the table, validation fails
        if (! $columnData) {
            return [
                'valid' => false,
                'data_type' => null,
                'value' => $value,
                'validation_error' => 'Invalid Column',
            ];
        }

        // Determine if the data type for the value matches expected value
        $isValid = $this->validateColumnValue(
            $columnData['type'],
            $value,
            $columnData['list'] ?? []
        );
        $errorMsg = ! $isValid ? $columnData['type'].' expected' : null;

        return [
            'valid' => $isValid,
            'data_type' => $columnData['type'],
            'value' => $this->castColumnValue($columnData['type'], $value),
            'validation_error' => $errorMsg,
        ];
    }

    /**
     * Cast the data value to the proper type
     */
    protected function castColumnValue(string $dataType, mixed $value): mixed
    {
        return match ($dataType) {
            'boolean' => filter_var(
                $value,
                FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE
            ),
            'integer' => (int) $value,
            default => (string) $value,
        };
    }

    /**
     * Verify that the data type for the value is valid
     */
    protected function validateColumnValue(string $dataType, mixed $value, array $listVal = []): bool
    {
        return match ($dataType) {
            'boolean' => filter_var(
                $value,
                FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE
            ) !== null,
            'enum' => in_array($value, $listVal),
            'integer' => is_numeric($value),
            default => is_string($value),
        };
    }
}

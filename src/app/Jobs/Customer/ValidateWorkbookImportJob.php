<?php

namespace App\Jobs\Customer;

use App\Events\Customer\WorkbookTableImportValidationEvent;
use App\Models\CustomerEquipmentWorkbook;
use App\Models\FileUpload;
use App\Services\Customer\CustomerWorkbookService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelReader;

class ValidateWorkbookImportJob implements ShouldQueue
{
    use Queueable;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        protected CustomerEquipmentWorkbook $workbook,
        protected string $tableIndex,
        protected FileUpload $file
    ) {}

    /**
     * Validate an imported CSV to push to a workbook table value
     */
    public function handle(CustomerWorkbookService $svc): void
    {
        Log::debug('Starting Validate Table Import Job', [
            'workbook_id' => $this->workbook->wb_id,
            'table_index' => $this->tableIndex,
            'file_upload_id' => $this->file->file_id,
        ]);

        $filePath = Storage::disk($this->file->disk)->path($this->file->folder);
        $tableHeaders = collect(
            $svc->getTableHeaderData($this->workbook, $this->tableIndex)
        );

        $writer = SimpleExcelReader::create(
            $filePath.DIRECTORY_SEPARATOR.$this->file->hash_name
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

        Log::debug('File Validation Import Completed', $validated->toArray());

        WorkbookTableImportValidationEvent::dispatch(
            $this->workbook,
            $this->tableIndex,
            $validated->toArray()
        );
    }
}

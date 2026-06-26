<?php

namespace App\Jobs\Customer;

use App\Actions\Customer\ValidateWorkbookImportFile;
use App\Events\Customer\WorkbookTableImportValiationEvent;
use App\Models\CustomerEquipmentWorkbook;
use App\Models\FileUpload;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ValidateWorkbookImportJob implements ShouldQueue
{
    use Queueable;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        protected CustomerEquipmentWorkbook $workbook,
        protected string $tableIndex,
        protected FileUpload $file,
        protected bool $isPagePublic,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $obj = new ValidateWorkbookImportFile;

        $validated = $obj($this->workbook, $this->tableIndex, $this->file);

        Log::critical('validated', $validated->toArray());

        Cache::put(
            $this->workbook->wb_hash.$this->tableIndex,
            [
                'data' => $validated->toArray(),
                'public' => $this->isPagePublic,
            ],
            86400
        );

        WorkbookTableImportValiationEvent::dispatch(
            $this->workbook,
            $this->tableIndex
        );
    }
}

<?php

namespace App\Jobs\Customer;

use App\Events\Customer\WorkbookTableImportCompletedEvent;
use App\Models\CustomerEquipmentWorkbook;
use App\Services\Customer\CustomerWorkbookService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class WorkbookImportTableDataJob implements ShouldQueue
{
    use Queueable;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        protected CustomerEquipmentWorkbook $workbook,
        protected string $tableIndex,
        protected array $validatedResults,
        protected int $chunkNum,
        protected int $totalChunks,
        protected bool $isPagePublic
    ) {}

    /**
     * Execute the job.
     */
    public function handle(CustomerWorkbookService $svc): void
    {
        // Set the max timeout for this action
        set_time_limit(600);

        $svc->importTableData(
            $this->workbook,
            $this->tableIndex,
            $this->validatedResults,
            $this->isPagePublic
        );

        WorkbookTableImportCompletedEvent::dispatch(
            $this->workbook,
            $this->tableIndex,
            $this->chunkNum,
            $this->totalChunks
        );
    }
}

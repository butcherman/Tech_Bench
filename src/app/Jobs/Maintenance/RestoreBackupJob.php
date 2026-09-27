<?php

namespace App\Jobs\Maintenance;

use App\Actions\Maintenance\RestoreBackup;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RestoreBackupJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected string $backupName) {}

    /**
     * Execute the job.
     */
    public function handle(RestoreBackup $action): void
    {
        $action->__invoke($this->backupName);
    }
}

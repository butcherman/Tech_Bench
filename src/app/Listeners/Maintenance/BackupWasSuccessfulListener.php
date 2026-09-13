<?php

namespace App\Listeners\Maintenance;

use App\Enums\BackupRunStatus;
use App\Enums\BackupType;
use App\Models\BackupRun;
use App\Services\Maintenance\BackupService;
use Illuminate\Support\Facades\Log;
use Spatie\Backup\Events\BackupWasSuccessful;

class BackupWasSuccessfulListener
{
    /**
     * Create the event listener.
     */
    public function __construct(protected BackupService $svc) {}

    /**
     * Handle the event.
     */
    public function handle(BackupWasSuccessful $event): void
    {
        $runningBackup = BackupRun::where('status', 'running')->get();

        if ($runningBackup->count() > 1) {
            Log::critical(
                'Database shows more than one active backup is currently running.',
                $runningBackup->toArray()
            );
        }

        if ($runningBackup->isEmpty()) {
            $backup = $this->svc->latest();

            BackupRun::create([
                'type' => BackupType::Unknown,
                'status' => BackupRunStatus::Completed,
                'backup_name' => $backup->name,
                'started_at' => now(),
                'completed_at' => now(),
                'size' => $backup->size,
            ]);
        }
    }
}

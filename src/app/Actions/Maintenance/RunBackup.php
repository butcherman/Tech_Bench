<?php

namespace App\Actions\Maintenance;

use App\Enums\BackupRunStatus;
use App\Enums\BackupType;
use App\Exceptions\Maintenance\BackupFailedException;
use App\Models\BackupRun;
use App\Services\Maintenance\BackupService;
use App\Services\Misc\ConsoleOutputService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Throwable;

class RunBackup
{
    public function __construct(protected BackupService $svc) {}

    public function handle(BackupType $type): void
    {
        Log::info('Starting Tech Bench backup.');

        $run = BackupRun::create([
            'type' => $type,
            'status' => BackupRunStatus::Running,
            'started_at' => now(),
        ]);

        try {
            $exitCode = Artisan::call(
                'backup:run',
                [],
                new ConsoleOutputService,
            );

            if ($exitCode !== 0) {
                throw new BackupFailedException(
                    'Tech Bench backup failed with exit code '.$exitCode.'.'
                );
            }

            $backup = $this->svc->latest();

            $run->update([
                'status' => BackupRunStatus::Completed,
                'backup_name' => $backup->name,
                'completed_at' => now(),
                'size' => $backup->size,
            ]);

            Log::info('Tech Bench backup completed successfully.');
        } catch (Throwable $e) {
            $run->update([
                'status' => BackupRunStatus::Failed,
                'completed_at' => now(),
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}

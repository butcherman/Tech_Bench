<?php

namespace App\Actions\Maintenance;

use App\Exceptions\Maintenance\BackupFailedException;
use App\Services\Misc\ConsoleOutputService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class RunBackup
{
    public function handle(): void
    {
        Log::info('Starting Tech Bench backup.');

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

        Log::info('Tech Bench backup completed successfully.');
    }
}

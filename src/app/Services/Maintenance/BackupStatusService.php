<?php

namespace App\Services\Maintenance;

use App\DTO\Maintenance\BackupStatus;
use Carbon\Carbon;

class BackupStatusService
{
    public function __construct(protected BackupService $backups) {}

    public function status(): BackupStatus
    {
        $all = $this->backups->all();
        $latest = $all->first();

        if (! $latest) {
            return new BackupStatus(
                healthy: false,
                latest: null,
                backupCount: 0,
                totalSize: 0,
                message: 'No backups have been created.',
            );
        }

        $maximumAge = (int) config(
            'backup.monitor_backups.0.health_checks.maximum_age_in_days',
            1
        );

        $healthy = $latest->started_at >= Carbon::now()->subDays($maximumAge);

        return new BackupStatus(
            healthy: $healthy,
            latest: $latest,
            backupCount: $all->count(),
            totalSize: $all->sum(
                fn ($backup) => $backup->size
            ),
            message: $healthy
                ? null
                : 'The most recent backup is older than expected.',
        );
    }
}

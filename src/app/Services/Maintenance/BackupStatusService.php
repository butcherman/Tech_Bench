<?php

namespace App\Services\Maintenance;

use App\DTO\Maintenance\BackupStatus;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class BackupStatusService
{
    public function __construct(
        protected BackupService $backups,
    ) {}

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

        $healthy = $latest->modified
            ->greaterThanOrEqualTo(
                CarbonImmutable::now()->subDays($maximumAge)
            );

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

    public function getNextScheduledBackup(): string
    {
        if (! config('backup.backup.nightly_backup')) {
            return 'Never';
        }

        $now = Carbon::now();
        $next3am = Carbon::today()->setTime(3, 0, 0);

        if ($now->gte($next3am)) {
            $next3am->addDay();
        }

        return $next3am->format('M d, Y h:00 A');
    }

    public function getRetentionPolicy()
    {
        $strategy = config('backup.cleanup.default_strategy');

        return [
            'daily' => $strategy['keep_daily_backups_for_days'],
            'weekly' => $strategy['keep_weekly_backups_for_weeks'],
            'monthly' => $strategy['keep_monthly_backups_for_months'],
            'yearly' => $strategy['keep_yearly_backups_for_years'],
        ];
    }
}

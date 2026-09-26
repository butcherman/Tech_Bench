<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Enums\DiskEnum;
use App\Http\Controllers\Controller;
use App\Services\File\StorageUsageService;
use App\Services\Maintenance\BackupService;
use App\Services\Maintenance\BackupSettingsService;
use App\Services\Maintenance\BackupStatusService;
use Inertia\Inertia;
use Inertia\Response;

class BackupIndexController extends Controller
{
    public function __construct(
        protected BackupService $backups,
        protected BackupStatusService $status,
        protected BackupSettingsService $settings,
        protected StorageUsageService $storage,
    ) {}

    public function __invoke(): Response
    {
        $this->authorize('is-installer');

        return Inertia::render('Maint/Backup/Index', [
            'status' => fn () => $this->status->status()->toArray(),
            'backups' => fn () => $this->backups
                ->recent(10)
                ->map
                ->toArray()
                ->values(),
            'next-run' => fn () => $this->backups->getNextScheduledBackup(),
            'strategy' => fn () => $this->backups->getRetentionPolicy(),
            'settings' => fn () => $this->settings->getBackupSettings(),
            'storage' => fn () => $this->storage->getUsage(DiskEnum::backups),
        ]);
    }
}

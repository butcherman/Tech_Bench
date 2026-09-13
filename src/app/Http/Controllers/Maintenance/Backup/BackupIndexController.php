<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Http\Controllers\Controller;
use App\Services\Maintenance\BackupService;
use App\Services\Maintenance\BackupStatusService;
use Inertia\Inertia;
use Inertia\Response;

class BackupIndexController extends Controller
{
    public function __construct(
        protected BackupService $backups,
        protected BackupStatusService $status,
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
        ]);
    }
}

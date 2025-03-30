<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Http\Controllers\Controller;
use App\Services\Maintenance\BackupService;
use Inertia\Inertia;
use Inertia\Response;

class BackupIndexController extends Controller
{
    public function __construct(protected BackupService $svc) {}

    /**
     * Show the list of backups for download and allow running of backup
     */
    public function __invoke(): Response
    {
        $this->authorize('is-installer');

        return Inertia::render('Maint/Backup', [
            'backup-list' => [], //  fn () => $this->svc->getBackupFiles(),
            'backup-running' => false, //  fn () => $this->svc->isBackupRunning(),
        ]);
    }
}

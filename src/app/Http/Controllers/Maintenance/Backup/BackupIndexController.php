<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Http\Controllers\Controller;
use App\Services\Maintenance\BackupService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BackupIndexController extends Controller
{
    public function __construct(protected BackupService $svc) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Maint/Backup', [
            'backup-list' => fn() => $this->svc->getBackupFiles(),
        ]);
    }
}

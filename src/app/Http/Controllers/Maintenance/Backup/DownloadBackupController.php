<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Models\BackupRun;
use App\Services\Maintenance\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadBackupController extends Controller
{
    public function __construct(
        protected BackupService $backups,
    ) {}

    public function __invoke(Request $request, BackupRun $backupName): StreamedResponse
    {
        $this->authorize('viewAny', AppSettings::class);

        Log::info(
            'Backup file being downloaded by '.$request->user()->username,
            $backupName->toArray()
        );

        return $this->backups->download($backupName);
    }
}

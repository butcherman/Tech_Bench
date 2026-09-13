<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Services\Maintenance\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadBackupController extends Controller
{
    public function __construct(
        protected BackupService $backups,
    ) {}

    public function __invoke(
        Request $request,
        string $backupName,
    ) { // : StreamedResponse
        $this->authorize('viewAny', AppSettings::class);

        // TODO - Step 12 - fix this
        // Log::info(
        //     'Backup file '.$backupName.' downloaded by '.
        //     $request->user()->username
        // );

        // return $this->backups
        //     ->disk()
        //     ->download(
        //         config('backup.backup.name').
        //         DIRECTORY_SEPARATOR.
        //         $backupName
        //     );
    }
}

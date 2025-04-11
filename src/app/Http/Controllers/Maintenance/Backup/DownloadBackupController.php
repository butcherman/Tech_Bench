<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Exceptions\Maintenance\BackupFileMissingException;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Services\Maintenance\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadBackupController extends Controller
{
    public function __construct(protected BackupService $svc) {}

    /**
     * Download a backup file
     */
    public function __invoke(Request $request, string $backupName): StreamedResponse
    {
        $this->authorize('viewAny', AppSettings::class);

        if (! $this->svc->doesBackupExist($backupName)) {
            throw new BackupFileMissingException($backupName);
        }

        Log::info(
            'Download file - '.$backupName.' downloaded by '.
                $request->user()->username
        );

        return Storage::disk('backups')->download(config('backup.backup.name').
            DIRECTORY_SEPARATOR.$backupName);
    }
}

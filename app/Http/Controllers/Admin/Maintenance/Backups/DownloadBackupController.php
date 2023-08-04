<?php

namespace App\Http\Controllers\Admin\Maintenance\Backups;

use App\Exceptions\BackupMissingException;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Download an existing backup
 */
class DownloadBackupController extends Controller
{
    public function __invoke(Request $request, string $backup)
    {
        $this->authorize('viewAny', AppSettings::class);

        // Verify Backup Exists
        if (! Storage::disk('backups')->exists(config('backup.backup.name').DIRECTORY_SEPARATOR.$backup)) {
            // If backup does not exist, throw 404 error
            throw new BackupMissingException($backup);
        }

        Log::info('Backup '.$backup.' downloaded by '.$request->user()->username);

        return Storage::disk('backups')->download(config('backup.backup.name').DIRECTORY_SEPARATOR.$backup);
    }
}

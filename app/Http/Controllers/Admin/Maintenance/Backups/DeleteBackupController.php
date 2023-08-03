<?php

namespace App\Http\Controllers\Admin\Maintenance\Backups;

use App\Exceptions\BackupMissingException;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DeleteBackupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $backup)
    {
        $this->authorize('viewAny', AppSettings::class);

        // Verify Backup Exists
        if(!Storage::disk('backups')->exists(config('backup.backup.name').DIRECTORY_SEPARATOR.$backup)) {
            throw new BackupMissingException($backup);
        }

        Storage::disk('backups')->delete(config('backup.backup.name').DIRECTORY_SEPARATOR.$backup);
        Log::notice('Backup file '.$backup.' deleted by '.$request->user()->username);

        return response()->noContent();
    }
}

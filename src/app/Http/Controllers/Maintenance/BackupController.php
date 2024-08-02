<?php

namespace App\Http\Controllers\Maintenance;

use App\Exceptions\Maintenance\BackupFileMissingException;
use App\Http\Controllers\Controller;
use App\Jobs\Maintenance\RunBackupJob;
use App\Models\AppSettings;
use App\Service\Maint\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BackupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', AppSettings::class);

        $obj = new BackupService;

        return Inertia::render('Maint/Backup', [
            'backup-running' => fn () => $obj->isBackupRunning(),
            'backup-list' => fn () => $obj->getBackupFiles(),
        ]);
    }

    /**
     * Manually start the backup process
     */
    public function store(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        dispatch(new RunBackupJob)->onQueue('backups');
        Log::info('Backup Operation called by '.$request->user()->username);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $backupName)
    {
        $this->authorize('viewAny', AppSettings::class);

        $bakObj = new BackupService;

        if (! $bakObj->validateBackupFile($backupName)) {
            throw new BackupFileMissingException($backupName);
        }

        Log::info('Download file - '.$backupName.' downloaded by '.$request->user()->username);

        return Storage::disk('backups')->download(config('backup.backup.name').
            DIRECTORY_SEPARATOR.$backupName);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $backupName)
    {
        $this->authorize('viewAny', AppSettings::class);

        $bakObj = new BackupService;

        if (! $bakObj->validateBackupFile($backupName)) {
            throw new BackupFileMissingException($backupName);
        }

        $bakObj->deleteBackupFile($backupName);

        Log::notice('Backup File '.$backupName.' deleted by '.$request->user()->username);

        return back()->with('success', __('admin.backups.deleted'));

    }
}

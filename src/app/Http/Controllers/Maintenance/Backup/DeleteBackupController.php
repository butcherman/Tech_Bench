<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Exceptions\Maintenance\BackupFileMissingException;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Services\Maintenance\BackupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeleteBackupController extends Controller
{
    public function __construct(protected BackupService $svc) {}

    /**
     * Delete A Backup File
     */
    public function __invoke(Request $request, string $backupName): RedirectResponse
    {
        $this->authorize('viewAny', AppSettings::class);

        if (! $this->svc->doesBackupExist($backupName)) {
            throw new BackupFileMissingException($backupName);
        }

        $this->svc->deleteBackupFile($backupName);

        Log::notice(
            'Backup File '.$backupName.' deleted by '.
                $request->user()->username
        );

        return back()->with('success', __('admin.backups.deleted'));
    }
}

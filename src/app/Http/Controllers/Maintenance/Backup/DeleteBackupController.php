<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Services\Maintenance\BackupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeleteBackupController extends Controller
{
    public function __construct(
        protected BackupService $backups,
    ) {}

    public function __invoke(
        Request $request,
        string $backupName,
    ): RedirectResponse {
        $this->authorize('viewAny', AppSettings::class);

        $this->backups->delete($backupName);

        Log::notice(
            'Backup file '.$backupName.' deleted by '.
            $request->user()->username
        );

        return back()->with(
            'success',
            __('admin.backups.deleted')
        );
    }
}

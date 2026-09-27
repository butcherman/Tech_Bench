<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\RestoreBackupRequest;
use App\Jobs\Maintenance\RestoreBackupJob;
use Inertia\Inertia;

class RestoreBackupController extends Controller
{
    /**
     * Restore a backup file.
     */
    public function __invoke(RestoreBackupRequest $request)
    {
        RestoreBackupJob::dispatch($request->input('selected'))
            ->onQueue('backups');

        return Inertia::render('Maint/Backup/Restore');
    }
}

<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Http\Controllers\Controller;
use App\Jobs\Maintenance\RunBackupJob;
use App\Models\AppSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Backup\Tasks\Backup\BackupJob;

class RunBackupController extends Controller
{
    /**
     * Manually start the Backup Process
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $this->authorize('viewAny', AppSettings::class);

        dispatch(new RunBackupJob);

        Log::info('Backup Operation called by ' . $request->user()->username);

        return back();
    }
}

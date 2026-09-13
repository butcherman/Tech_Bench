<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Http\Controllers\Controller;
use App\Jobs\Maintenance\RunBackupJob;
use App\Models\AppSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RunBackupController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $this->authorize('viewAny', AppSettings::class);

        RunBackupJob::dispatch();

        Log::info(
            'Backup operation requested by '.$request->user()->username
        );

        return back()->with('success', 'Backup has been queued.');
    }
}

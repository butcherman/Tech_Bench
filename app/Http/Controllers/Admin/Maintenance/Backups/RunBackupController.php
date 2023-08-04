<?php

namespace App\Http\Controllers\Admin\Maintenance\Backups;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

/**
 * Manually run a backup
 */
class RunBackupController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        Artisan::queue('backup:run');
        Log::info('Backup Manually called by '.$request->user()->username);

        return response()->noContent();
    }
}

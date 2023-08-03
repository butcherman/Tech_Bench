<?php

namespace App\Http\Controllers\Admin\Maintenance\Backups;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class RunBackupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        Log::info('Backup Manually called by '.$request->user()->username);
        Artisan::queue('backup:run');

        return response()->noContent();
    }
}

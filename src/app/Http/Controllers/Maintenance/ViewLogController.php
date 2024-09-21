<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Service\Maint\LogParsingService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewLogController extends Controller
{
    /**
     * Show entries for a specific log file
     */
    public function __invoke(Request $request, string $channel, string $logFile)
    {
        $this->authorize('viewAny', AppSettings::class);
        $logObj = new LogParsingService;

        $logObj->validateLogFile($channel, $logFile);

        return match ($logObj->getChannelType($channel)) {
            'app' => Inertia::render('Maint/LogAppView', $logObj->getLogFileData($channel, $logFile)),
            default => abort(404),
        };
    }
}

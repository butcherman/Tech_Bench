<?php

namespace App\Http\Controllers\Maintenance\Logs;

use App\Exceptions\Maintenance\LogFileMissingException;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Services\Maintenance\AppLogParsingService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ViewLogController extends Controller
{
    public function __construct(protected AppLogParsingService $svc) {}

    /**
     * View a log file details
     */
    public function __invoke(Request $request, string $channel, string $logFile): Response
    {
        $this->authorize('viewAny', AppSettings::class);

        if (!$this->svc->validateLogFile($channel, $logFile)) {
            throw new LogFileMissingException($logFile);
        }

        return Inertia::render('Maint/AppLogView', [
            'log-file' => $logFile,
            'log-data' => $this->svc->getLogFileData($channel, $logFile),
        ]);
    }
}

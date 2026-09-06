<?php

namespace App\Http\Controllers\Maintenance\Logs;

use App\Actions\Maintenance\ParseLogFile;
use App\Exceptions\Maintenance\LogFileMissingException;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Services\Maintenance\AppLogParsingService;
use Inertia\Inertia;
use Inertia\Response;

class ViewLogController extends Controller
{
    public function __construct(protected AppLogParsingService $svc) {}

    /**
     * View a log file details
     */
    public function __invoke(ParseLogFile $parse, string $logFile): Response
    {
        $this->authorize('viewAny', AppSettings::class);

        throw_unless(
            $this->svc->validateLogFile($logFile),
            LogFileMissingException::class,
            $logFile
        );

        return Inertia::render('Maint/Logs/Index', [
            'levels' => $this->svc->getLogLevels(),
            'log-data' => Inertia::defer(fn () => $parse($logFile)),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Maintenance\Logs;

use App\Actions\Maintenance\CalculateLogStatistics;
use App\DTO\Maintenance\LogFilter;
use App\Exceptions\Maintenance\LogFileMissingException;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Services\Maintenance\LogUtilitiesService;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class LogsIndexController extends Controller
{
    public function __construct(
        protected LogUtilitiesService $svc,
        protected CalculateLogStatistics $stats
    ) {}

    /**
     * Show a listing of Log Channels and Logs in that Channel
     */
    public function __invoke(?string $logFile = null): Response
    {
        $this->authorize('viewAny', AppSettings::class);

        // If a log file is not selected, show today's log
        if (is_null($logFile)) {
            $today = Carbon::now();
            $logFile = 'TechBench-'.$today->format('Y-m-d');
        }

        throw_unless(
            $this->svc->validateLogFile($logFile),
            LogFileMissingException::class,
            $logFile
        );

        return Inertia::render('Maint/Logs/Index', [
            'logFile' => $logFile,
            'logList' => $this->svc->getListOfLogFiles(),
            'loggingLevel' => config('logging.channels.app.level'),
            'logData' => Inertia::defer(
                fn () => $this->svc->query(
                    $logFile,
                    $this->svc->snapshot($logFile),
                    new LogFilter,
                    1,
                )
            ),
            'logStats' => Inertia::defer(
                fn () => ($this->stats)($logFile)
            ),
        ]);
    }
}

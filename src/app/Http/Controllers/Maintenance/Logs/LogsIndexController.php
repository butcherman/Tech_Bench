<?php

namespace App\Http\Controllers\Maintenance\Logs;

use App\Actions\Maintenance\CalculateLogStatics;
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
        protected CalculateLogStatics $stats
    ) {}

    /**
     * Show a listing of Log Channels and Logs in that Channel
     */
    public function __invoke(): Response
    {
        $this->authorize('viewAny', AppSettings::class);

        $today = Carbon::now();
        $todaysLog = 'TechBench-'.$today->format('Y-m-d');

        throw_unless(
            $this->svc->validateLogFile($todaysLog),
            LogFileMissingException::class,
            $todaysLog
        );

        return Inertia::render('Maint/Logs/Index', [
            'logFile' => $todaysLog,
            'loggingLevel' => config('logging.channels.app.level'),
            'logData' => Inertia::defer(
                fn () => $this->svc->entries(
                    $todaysLog,
                    1,
                )
            ),
            'logStats' => Inertia::defer(
                fn () => ($this->stats)($todaysLog)
            ),

            // 'stats' => Inertia::defer(
            //     fn () => $this->svc->stats($todaysLog)
            // ),
        ]);
    }
}

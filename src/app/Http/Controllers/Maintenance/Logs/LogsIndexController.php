<?php

namespace App\Http\Controllers\Maintenance\Logs;

use App\Actions\Maintenance\ParseLogFile;
use App\Exceptions\Maintenance\LogFileMissingException;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Services\Maintenance\LogUtilitiesService;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class LogsIndexController extends Controller
{
    public function __construct(protected LogUtilitiesService $svc) {}

    /**
     * Show a listing of Log Channels and Logs in that Channel
     */
    public function __invoke(ParseLogFile $parse): Response
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
            'log-data' => Inertia::defer(fn () => $parse($todaysLog)),
        ]);
    }
}

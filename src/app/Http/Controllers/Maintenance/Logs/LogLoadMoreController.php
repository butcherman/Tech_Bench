<?php

namespace App\Http\Controllers\Maintenance\Logs;

use App\DTO\Maintenance\LogFilter;
use App\DTO\Maintenance\LogSnapshot;
use App\Exceptions\Maintenance\LogFileMissingException;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Services\Maintenance\LogUtilitiesService;
use Illuminate\Http\Request;

class LogLoadMoreController extends Controller
{
    public function __construct(protected LogUtilitiesService $svc) {}

    /**
     * Fetch additional data from the log
     */
    public function __invoke(Request $request, string $logFile)
    {
        $this->authorize('viewAny', AppSettings::class);

        throw_unless(
            $this->svc->validateLogFile($logFile),
            LogFileMissingException::class,
            $logFile
        );

        if ($request->has('snapshot')) {
            $snapshot = new LogSnapshot($request->input('snapshot'));
        } else {
            $snapshot = $this->svc->snapshot($logFile);
        }

        $filter = new LogFilter(
            $request->input('search') ?? null,
            $request->input('level') ?? null,
            $request->input('user') ?? null,
            $request->input('traceId') ?? null
        );

        return response()->json(
            $this->svc->query(
                $logFile,
                $snapshot,
                $filter,
                $request->input('page'),
            ),
        );
    }
}

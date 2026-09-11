<?php

namespace App\Http\Controllers\Maintenance\Logs;

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
        }

        return response()->json(
            $this->svc->entries(
                $logFile,
                $request->input('page'),
                $snapshot ?? null,
            ),
        );
    }
}

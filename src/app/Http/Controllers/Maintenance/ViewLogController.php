<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Traits\LogUtilitiesTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewLogController extends Controller
{
    use LogUtilitiesTrait;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $channel, string $logFile)
    {
        $this->authorize('viewAny', AppSettings::class);
        $this->validateChannel($channel);

        $filePath = $this->getLogFile($channel, $logFile);
        $fileArray = $this->getFileToArray($filePath);

        return Inertia::render('Maint/LogView', [
            'levels' => fn() => $this->logLevels,
            'channel' => fn() => $channel,
            'filename' => fn() => $logFile,
            'file-stats' => fn() => [$this->getFileStats($fileArray)],
            'file-entries' => fn() => array_reverse($this->parseLogFile($fileArray)),
        ]);
    }
}

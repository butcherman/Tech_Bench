<?php

namespace App\Http\Controllers\Maintenance\Logs;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Services\Maintenance\LogUtilitiesService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LogsIndexController extends Controller
{
    public function __construct(protected LogUtilitiesService $svc) {}

    /**
     * Show a listing of Log Channels and Logs in that Channel
     */
    public function __invoke(?string $channel = null)
    {
        $this->authorize('viewAny', AppSettings::class);

        if ($channel) {
            dd($channel);
        }

        return Inertia::render('Maint/LogIndex', [
            'channels' => [],
            'log-levels' => $this->svc->getLogLevels(),
        ]);
    }
}

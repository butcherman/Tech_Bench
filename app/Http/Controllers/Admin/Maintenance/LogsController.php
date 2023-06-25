<?php

namespace App\Http\Controllers\Admin\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Traits\LogUtilitiesTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LogsController extends Controller
{
    use LogUtilitiesTrait;

    /**
     * Display a listing of the resource.
     */
    public function __invoke(string $channel = null)
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Logs/Index', [
            'channels' => $this->getLogChannels(),
            'levels' => $this->getLogLevels(),
            'channel' => $channel,
            'log-list' => $channel ? $this->getLogList($channel) : null,
        ]);
    }
}

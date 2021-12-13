<?php

namespace App\Http\Controllers\Admin\Logs;

use Inertia\Inertia;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;

use App\Models\AppSettings;
use App\Traits\LogUtilitiesTrait;
use App\Http\Controllers\Controller;

class LogSettingsController extends Controller
{
    use LogUtilitiesTrait;

    /**
     * Adjust the settings for log files
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Logs/Settings', [
            'log_level' => config('logging.log_level'),
            'days'      => config('logging.days'),
            'types'     => array_map('strtolower', Arr::pluck($this->logLevels, 'name')),
        ]);
    }
}

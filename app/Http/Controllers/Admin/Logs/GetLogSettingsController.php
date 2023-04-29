<?php

namespace App\Http\Controllers\Admin\Logs;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Traits\LogUtilitiesTrait;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class GetLogSettingsController extends Controller
{
    use LogUtilitiesTrait;

    /**
     * Form for changing the log settings
     */
    public function __invoke()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Logs/Settings', [
            'log-level' => config('logging.log_level'),
            'days' => config('logging.days'),
            'types' => array_map('strtolower', Arr::pluck($this->logLevels, 'name')),
        ]);
    }
}

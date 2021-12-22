<?php

namespace App\Http\Controllers\Admin\Config;

use Inertia\Inertia;

use App\Models\AppSettings;
use App\Http\Controllers\Controller;

class GetConfigController extends Controller
{
    /**
     * Show the application configuration
     */
    public function __invoke()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/App/Config', [
            'settings' => [
                'url'            => config('app.url'),
                'timezone'       => config('app.timezone'),
                'filesize'       => config('filesystems.max_filesize'),
            ],
            'tz_list' => \Timezonelist::toArray(),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Inertia\Inertia;

class GetConfigController extends Controller
{
    /**
     * Application Configuration page
     */
    public function __invoke()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/App/Config', [
            'settings' => [
                'url' => config('app.url'),
                'timezone' => config('app.timezone'),
                'filesize' => config('filesystems.max_filesize'),
                'redirectUri' => config('app.url').'/auth/callback',
            ],
            'tz_list' => \Timezonelist::toArray(),
        ]);
    }
}

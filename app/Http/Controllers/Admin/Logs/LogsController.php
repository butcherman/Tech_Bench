<?php

namespace App\Http\Controllers\Admin\Logs;

use Inertia\Inertia;
use Illuminate\Support\Arr;
use App\Http\Controllers\Controller;
use App\Traits\LogUtilitiesTrait;
use App\Models\AppSettings;

class LogsController extends Controller
{
    use LogUtilitiesTrait;

    /**
     * System Logs landing page
     */
    public function __invoke()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Logs/Index', [
            'channels' => Arr::pluck($this->logChannels, 'name'),
        ]);
    }
}

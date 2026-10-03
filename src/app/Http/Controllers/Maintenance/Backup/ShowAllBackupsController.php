<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Services\Maintenance\BackupService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShowAllBackupsController extends Controller
{
    public function __construct(protected BackupService $svc) {}

    /**
     * List all backup files.
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Maint/Backup/Show', [
            'backups' => $this->svc
                ->all()
                ->map
                ->toArray()
                ->values(),
        ]);
    }
}

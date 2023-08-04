<?php

namespace App\Http\Controllers\Admin\Maintenance\Backups;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Inertia\Inertia;

/**
 * Backup Landing page for backup operations
 */
class BackupController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Backups/Index');
    }
}

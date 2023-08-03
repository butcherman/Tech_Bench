<?php

namespace App\Http\Controllers\Admin\Maintenance\Backups;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BackupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Backups/Index');
    }
}

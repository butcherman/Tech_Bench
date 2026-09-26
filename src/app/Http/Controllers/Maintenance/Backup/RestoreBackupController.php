<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\RestoreBackupRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RestoreBackupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RestoreBackupRequest $request)
    {
        return Inertia::render('Maint/Backup/Restore');
    }
}

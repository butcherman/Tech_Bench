<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Actions\Maintenance\ProcessUploadedBackup;
use App\Http\Controllers\FileUploadController;
use App\Http\Requests\Maintenance\UploadBackupRequest;
use App\Models\AppSettings;
use Exception;
use Inertia\Inertia;
use Inertia\Response;

class UploadBackupController extends FileUploadController
{
    public function __construct(protected ProcessUploadedBackup $action) {}

    /**
     * Show form to upload a backup file
     */
    public function create(): Response
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Maint/Backup/Create');
    }

    /**
     * Save an uploaded backup file.
     */
    public function store(UploadBackupRequest $request)
    {
        try {
            ($this->action)($request->input('upload_id'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'File Uploaded');
    }
}

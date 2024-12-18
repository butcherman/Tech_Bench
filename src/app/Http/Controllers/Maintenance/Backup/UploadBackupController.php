<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Enums\DiskEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FileUploadController;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;


class UploadBackupController extends FileUploadController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        $this->setFileData(DiskEnum::backups, 'tech-bench');

        if ($savedFile = $this->getChunk($request->file('file'), $request)) {
            Log::info('New Backup File Uploaded ' . $savedFile->file_name);
        }

        return response()->noContent();
    }
}

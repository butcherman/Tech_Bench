<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadBackupController extends Controller
{
    use FileTrait;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        $this->setFileData('backups', 'tech-bench');

        if ($savedFile = $this->getChunk($request)) {
            Log::info('New Backup File Uploaded '.$savedFile->file_name);
        }

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers\Admin\Maintenance\Backups;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Gather a listing of all backup files.  Reverse the order to put the newest file on top
 */
class FetchBackupsController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        return array_reverse(str_replace(config('backup.backup.name').DIRECTORY_SEPARATOR, '', Storage::disk('backups')->files('tech-bench')));
    }
}

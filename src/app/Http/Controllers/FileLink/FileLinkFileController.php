<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Models\FileLink;
use App\Models\FileUpload;
use App\Services\FileLink\FileLinkFileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FileLinkFileController extends Controller
{
    public function __construct(protected FileLinkFileService $svc) {}

    /**
     * Save a new file to the File Link
     */
    public function store(Request $request)
    {
        //
        return 'store';
    }

    /**
     * Delete a file from the File Link
     */
    public function destroy(FileLink $link, FileUpload $file): RedirectResponse
    {
        $this->authorize('update', $link);

        $this->svc->destroyFileLinkFile($link, $file);

        return back()->with('warning', 'File Deleted');
    }
}

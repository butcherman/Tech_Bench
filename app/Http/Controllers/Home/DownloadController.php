<?php

namespace App\Http\Controllers\Home;

use App\Events\Home\DownloadedFileEvent;
use App\Http\Controllers\Controller;
use App\Models\FileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    /**
     * Download a file stored in the database
     */
    public function __invoke($fileId, $filename)
    {
        //  Verify file ID and filename match
        $file = FileUploads::where('file_id', $fileId)->where('file_name', $filename)->firstOrFail();

        //  If file is not being downloaded by user, verify file is public accessable
        if(!Auth::check() && !$file->public)
        {
            abort(403, 'You do not have permission to download this file');
        }

        //  Verify that the file is in place
        if(!Storage::disk($file->disk)->exists($file->folder.DIRECTORY_SEPARATOR.$file->file_name))
        {
            abort(404, 'Unable to find the file specified');
        }

        event(new DownloadedFileEvent($file));
        return Storage::disk($file->disk)->download($file->folder.DIRECTORY_SEPARATOR.$file->file_name);
    }
}

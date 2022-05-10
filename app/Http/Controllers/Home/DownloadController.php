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

        //  Download the file
        $path     = Storage::disk($file->disk)->path($file->folder.DIRECTORY_SEPARATOR.$file->file_name);
        $fileName = basename($path);

        //  Prepare header information for file download
        header('Content-Description:  File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($fileName));
        header('Content-Transfer-Encoding:  binary');
        header('Expires:  0');
        header('Cache-Control:  must-revalidate, post-check=0, pre-check=0');
        header('Pragma:  public');
        header('Content-Length:  '.filesize($path));

        //  Begin the file download.  File is broken into sections to better be handled by browser
        set_time_limit(0);
        $file = fopen($path, "rb");
        while(!feof($file))
        {
            print(@fread($file, 1024 * 8));
            ob_flush();
            flush();
        }
    }
}

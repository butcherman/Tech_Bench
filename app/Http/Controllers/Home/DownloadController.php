<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\FileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, int $fileId, string $filename)
    {
        //  Verify file ID and filename match
        $file = FileUploads::where('file_id', $fileId)->where('file_name', $filename)->firstOrFail();

        //  If file is not being downloaded by user, verify file is public accessible
        if (! Auth::check() && ! $file->public) {
            Log::alert('Someone is trying to download a file that they do not have permission to download', [
                'file_name' => $filename,
                'file_id' => $fileId,
                'authorized' => Auth::check() ? Auth::user()->username : false,
                'ip_address' => \Request::ip(),
            ]);
            abort(403, 'You do not have permission to download this file');
        }

        //  Verify that the file is in place
        if (! Storage::disk($file->disk)->exists($file->folder.DIRECTORY_SEPARATOR.$file->file_name)) {
            Log::alert('Unable to find a file that a user is trying to download', [
                'file_name' => $filename,
                'path' => $file->folder.DIRECTORY_SEPARATOR.$file->file_name,
                'disk' => $file->disk,
                'file_id' => $fileId,
                'authorized' => Auth::check() ? Auth::user()->username : false,
                'ip_address' => \Request::ip(),
            ]);
            abort(404, 'Unable to find the file specified');
        }

        //  Download the file
        // @codeCoverageIgnoreStart
        $path = Storage::disk($file->disk)->path($file->folder.DIRECTORY_SEPARATOR.$file->file_name);
        $fileName = basename($path);

        Log::info('File being downloaded', [
            'path' => $path,
            'file_name' => $fileName,
            'public' => $file->public,
            'user' => $request->user() ? $request->user()->username : null,
            'ip_address' => $request->ip(),
        ]);

        //  Prepare header information for file download
        header('Content-Description:  File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($fileName));
        header('Content-Transfer-Encoding:  binary');
        header('Expires:  0');
        header('Cache-Control:  must-revalidate, post-check=0, pre-check=0');
        header('Pragma:  public');
        header('Content-Length:  '.filesize($path));

        //  Begin the file download.  File is streamed in chunks for smoother download
        set_time_limit(0);
        $file = fopen($path, 'rb');
        while (! feof($file)) {
            echo @fread($file, 1024 * 8);
            ob_flush();
            flush();
        }
        // @codeCoverageIgnoreEnd
    }
}

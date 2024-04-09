<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DownloadFileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, FileUpload $file, string $fileName)
    {
        $file->validateFile($fileName);

        // @codeCoverageIgnoreStart
        $path = $file->getFilePath();
        $fileName = basename($path);

        Log::info('File being downloaded', [
            'path' => $path,
            'file_name' => $fileName,
            'public' => $file->public,
            'user' => $request->user() ? $request->user()->username : null,
            'ip_address' => $request->ip(),
            'file_data' => $file->toArray(),
        ]);

        //  Prepare header information for file download
        header('Content-Description:  File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($fileName));
        header('Content-Transfer-Encoding:  binary');
        header('Expires:  0');
        header('Cache-Control:  must-revalidate, post-check=0, pre-check=0');
        header('Pragma:  public');
        header('Content-Length:  ' . filesize($path));

        //  Begin the file download.  File is streamed in chunks for smoother download
        set_time_limit(0);
        $file = fopen($path, 'rb');
        while (!feof($file)) {
            echo @fread($file, 1024 * 8);
            ob_flush();
            flush();
        }
        // @codeCoverageIgnoreEnd
    }
}

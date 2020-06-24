<?php

namespace App\Http\Controllers;

use App\Domains\Files\GetFiles;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DownloadController extends Controller
{
    public function index($fileID, $fileName)
    {
        $fileObj = new GetFiles;
        if(!$path = $fileObj->validateFile($fileID, $fileName))
        {
            abort(404, 'The file you are looking for cannot be found');
        }

        if(!$fileObj->canUserDownload($fileID, Auth::check()))
        {
            abort(403, 'You do not have permission to download this file');
        }

        $this->downloadFile(config('filesystems.disks.local.root').DIRECTORY_SEPARATOR.$path);
    }

    //  Download the file in chunks to allow for large file download
    protected function downloadFile($path)
    {
        $fileName = basename($path);

        //  Prepare header information for file download
        header('Content-Description:  File Transfer');
        // header('Content-Type:  '.$fileData->mime_type);
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

        return true;
    }
}

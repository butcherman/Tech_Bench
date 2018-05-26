<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Files;

class DownloadController extends Controller
{
    //  Class to download the file
    public function index($fileID, $fileName)
    {
        $fileData = Files::find($fileID)->where('file_name', $fileName)->first();
        //  Check that the file exists before allowing it to be downloaded
        if(!empty($fileData) && Storage::exists($fileData->file_link.$fileData->file_name))
        {
            
            return Storage::download($fileData->file_link.$fileData->file_name);
        }
        
        return response('no file found', 404);
    }
}

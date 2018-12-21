<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Files;

class DownloadController extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
//        $this->middleware('auth');
    }
    
    //  Class to download the file
    public function index($fileID, $fileName)
    {
        $fileData = Files::where('file_id', $fileID)->where('file_name', $fileName)->first();
        //  Check that the file exists before allowing it to be downloaded
        if (!empty($fileData) && Storage::exists($fileData->file_link.$fileData->file_name))
        {
            Log::info('File Downloaded', ['file_id' => $fileID]);
            return Storage::download($fileData->file_link.$fileData->file_name);
        }
        
        Log::info('File Not Found', ['file_id' => $fileID, 'file_name' => $fileName]);
        return view('errors.badFile');
    }
}

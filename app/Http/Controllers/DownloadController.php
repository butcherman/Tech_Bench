<?php

namespace App\Http\Controllers;

use PDF;
use Zip;
use App\Files;
use App\Customers;
use App\CustomerNotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    //  Download one file
    public function index($fileID, $fileName)
    {
        $fileData = Files::where('file_id', $fileID)->where('file_name', $fileName)->first();
        //  Check that the file exists before allowing it to be downloaded
        if(!empty($fileData) && Storage::exists($fileData->file_link.$fileData->file_name))
        {
            Log::info('File Downloaded', ['file_id' => $fileID]);
            return Storage::download($fileData->file_link.$fileData->file_name);
        }
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::notice('File Not Found', ['file_id' => $fileID, 'file_name' => $fileName]);
        return view('err.badFile');
    }
    
    //  Put the download files into the flash data
    public function flashDownload(Request $request)
    {
        session()->flash('fileArr', $request->fileArr);
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return response()->json($request);
    }
    
    //  Download an array of files
    public function downloadAll()
    {
        //  Get files and base path
        $fileData = Files::findMany(session('fileArr'));
        $path = config('filesystems.disks.local.root').DIRECTORY_SEPARATOR;
        
        //  Verify that the file array is not empty
        if(empty($fileData))
        {
            Log::info('Files Not Found', [session('fileArr')]);
            return view('err.badFile');
        }
        
        //  Package the files in a zip file 
        $zip = Zip::create($path.'download.zip');
        foreach($fileData as $file)
        {
            $zip->add($path.$file->file_link.$file->file_name);
        }
        $zip->close();
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        
        //  Download zip file and remove it from the server
        return response()->download($path.'download.zip')->deleteFileAfterSend(true);
    }
    
    //  Download Customer Note as PDF
    public function downloadCustNote($id)
    {
        $note = CustomerNotes::find($id);
        $cust = Customers::find($note->cust_id);
        
        $pdf = PDF::loadView('pdf.customerNote', [
            'cust_name'   => $cust->name,
            'note_subj'   => $note->subject,
            'description' => $note->description
        ]);
        
        return $pdf->download($cust->name.' - Note: '.$note->subject.'.pdf');
    }
}

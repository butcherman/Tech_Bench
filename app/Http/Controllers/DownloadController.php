<?php

namespace App\Http\Controllers;

use PDF;
use Zip;
use App\Files;
use App\TechTips;
use App\Customers;
use Carbon\Carbon;
use App\CustomerNotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// use ZanySoft\Zip;

class DownloadController extends Controller
{
    //  File locations for stored files
    private $tmpFolder = 'archive_downloads'.DIRECTORY_SEPARATOR;
    private $root;
    private $path;

    public function __construct()
    {
        $this->root = config('filesystems.disks.local.root').DIRECTORY_SEPARATOR;
        $this->path = $this->root.$this->tmpFolder;
    }

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

    //  Package multiple files and prepare them for download
    public function archiveFiles(Request $request)
    {
        //  Validate the array
        $request->validate([
            'fileList' => 'required'
        ]);

        //  Filename of zip archive
        $name = 'zip_archive_'.Carbon::now()->timestamp.'.zip';

        //  The archive_downloads directory does not exist by default.  Touching a file in it ensures the directory is created and usable
        Storage::put($this->tmpFolder.'.ignore', '');

        //  Create the zip file
        $zip = Zip::create($this->path.$name);
        foreach($request->fileList as $file)
        {
            //  Place each file inside of the zip
            $data = Files::find($file);
            if($data->count() > 0)
            {
                // Log::debug('file exists', $data->toArray());
                $zip->add($this->root.$data->file_link.$data->file_name);
                Log::debug('File Added - '.$this->root.$data->file_link.$data->file_name);
            }
            else
            {
                Log::notice('User tried to download an empty zip archive.');
            }
        }
        //  Close zip file to be processed
        $zip->close();

        //  Return the name of the zip file
        return response()->json(['archive' => $name]);
    }

    //  Download multiple files as part of a zip archive that was put together
    public function downloadArchive($fileName)
    {
        //  Check if the requested archive exists
        if(Storage::exists($this->tmpFolder.$fileName))
        {
            Log::info('Archive Downloaded - '.$fileName);
            // return Storage::download($this->tmpFolder.$fileName);
            return response()->download($this->path.$fileName, 'download.zip')->deleteFileAfterSend(true);
        }

        Log::notice('Archive Not Found - '.$fileName);
        return view('err.badFile');
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

    //  Download Tech Tip as PDF
    public function downloadTechTip($id)
    {
        //  TODO - Makt this a better looking pdf
        $tip = TechTips::where('tip_id', $id)->with('User')->with('SystemTypes')->first();

        $pdf = PDF::loadView('pdf.techTip', [
            'data' => $tip,
            'comments' => collect([])
        ]);

        return $pdf->download('Tech Tip - '.$tip->subject.'.pdf');
    }
}

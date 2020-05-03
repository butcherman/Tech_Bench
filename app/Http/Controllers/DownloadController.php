<?php

namespace App\Http\Controllers;

use PDF;
use Zip;
use App\Files;
use App\TechTips;
use App\Customers;
use Carbon\Carbon;
use App\CustomerNotes;
use App\Domains\DownloadDomain;
use App\Http\Requests\DownloadArchiveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\File;

class DownloadController extends Controller
{
    //  File locations for stored files
    protected $tmpFolder = 'archive_downloads'.DIRECTORY_SEPARATOR;
    protected $root;
    protected $path;

    public function __construct()
    {
        $this->root = config('filesystems.disks.local.root').DIRECTORY_SEPARATOR;
        $this->path = $this->root.$this->tmpFolder;
    }

    //  Download one file
    public function index($fileID, $fileName)
    {
        $fileObj = new DownloadDomain;
        $fileObj->setFileDetails($fileID, $fileName);

        if($fileObj->isFileValid() && $fileObj->canUserDownload())
        {
            $path = config('filesystems.disks.local.root').$fileObj->getFileLinkForDownload();
            if(!$this->downloadFile($path))
            {
                abort(500);
            }

            exit;
        }

        return view('err.badFile');
    }

    //  Create an archive of files and download them
    public function archive(DownloadArchiveRequest $request)
    {
        $fileObj = new DownloadDomain;
        $archive = $fileObj->createArchive($request->fileList);

        return response()->json(['archive' => $archive]);
    }

    //  Download multiple files as part of a zip archive that was put together
    public function downloadArchive($fileName)
    {
        $fileObj = new DownloadDomain;
        if($aboslutePath = $fileObj->validateArchive($fileName))
        {
            if(!$this->downloadFile($aboslutePath))
            {
                abort(500);
            }

            $fileObj->deleteArchive($fileName);
            exit;
        }

        return view('err.badFile');
    }






    //  Download Customer Note as PDF
    public function downloadCustNote($id)
    {
        //  Debug Data
        $this->middleware('auth');
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        $note = CustomerNotes::find($id);
        $cust = Customers::find($note->cust_id);

        $pdf = PDF::loadView('pdf.customerNote', [
            'cust_name'   => $cust->name,
            'note_subj'   => $note->subject,
            'description' => $note->description
        ]);

        Log::info('Customer note downloaded by '.Auth::user()->full_name.'. Data: ', ['Customer ID: ' => $cust->id, 'Customer Name: ' => $cust->name, 'Note ID: ' => $id, 'Note Subject: ' => $note->subject]);
        return $pdf->download($cust->name.' - Note: '.$note->subject.'.pdf');
    }

    //  Download Tech Tip as PDF
    public function downloadTechTip($id)
    {
        //  Debug Data
        $this->middleware('auth');
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);

        //  TODO - Makt this a better looking pdf
        $tip = TechTips::where('tip_id', $id)->with('User')->with('SystemTypes')->first();

        $pdf = PDF::loadView('pdf.techTip', [
            'data' => $tip,
            'comments' => collect([])
        ]);

        Log::info('Tech Tip downloaded as PDF by '.Auth::user()->full_name.'.  Data: ', ['Tip ID: ' => $tip->tip_id, 'Subject: ' => $tip->subject]);
        return $pdf->download('Tech Tip - '.$tip->subject.'.pdf');
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

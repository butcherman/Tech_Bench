<?php

namespace App\Http\Controllers\Customers;

use App\Files;
use App\Customers;
use App\CustomerFiles;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;

class CustomerFilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Store a new customer file
    public function store(Request $request)
    {
        //  Validate the form
        $request->validate([
            'custID' => 'required',
            'name'   => 'required',
            'type'   => 'required'
        ]);
        
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
        
        //  Verify that the upload is valid and being processed
        if($receiver->isUploaded() === false)
        {
            Log::error('Upload File Missing - '.$request->toArray());
            throw new UploadMissingFileException();
        }
        
        //  Receive and process the file
        $save = $receiver->receive();
        
        //  See if the upload has finished
        if($save->isFinished())
        {
            //  Set file locationi and clean filename for duplicates
            $filePath = config('filesystems.paths.customers').DIRECTORY_SEPARATOR.$request->custID;
            $fileName = Files::cleanFileName($filePath, $request->file->getClientOriginalName());

            //  Store the file
            $request->file->storeAs($filePath, $fileName);

            //  Put the file in the database
            $file = Files::create(
            [
                'file_name' => $fileName,
                'file_link' => $filePath.DIRECTORY_SEPARATOR
            ]);

            //  Get information for system files table
            $fileID = $file->file_id;

            //  Input the file into the customer files table
            CustomerFiles::create([
                'file_id'      => $fileID,
                'file_type_id' => $request->type,
                'cust_id'      => $request->custID,
                'user_id'      => Auth::user()->user_id,
                'name'         => $request->name
            ]);
            
            Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
            Log::debug('Submitted Data - ', $request->toArray());
            Log::info('File added for Customer ID-'.$request->custID.' by User ID-'.Auth::user()->user_id.'.  New File ID-'.$fileID);
        }
        
        //  Get the current progress
        $handler = $save->handler();

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('File being uploaded.  Percentage done - '.$handler->getPercentageDone());
        return response()->json([
            'done'   => $handler->getPercentageDone(),
            'status' => true
        ]);
    }

    //  Get the files for the customer
    public function show($id)
    {
        $files = CustomerFiles::where('cust_id', $id)
            ->select('name', 'customer_file_types.description as type', 
            'first_name', 'last_name', 'customer_files.updated_at as added_on',
            'files.file_id', 'files.file_name', 'cust_file_id')
            ->LeftJoin('customer_file_types', 'customer_files.file_type_id', '=', 'customer_file_types.file_type_id')
            ->LeftJoin('users', 'customer_files.user_id', '=', 'users.user_id')
            ->Join('files', 'customer_files.file_id', '=', 'files.file_id')
            ->orderBy('customer_files.file_type_id', 'asc')
            ->get();
        
        return response()->json($files);
    }

    //  Remove a customer file
    public function destroy($id)
    {
        //  Remove the file from SystemFiles table
        $data = CustomerFiles::find($id);
        $fileID = $data->file_id;
        $data->delete();
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::info('File Deleted For Customer ID-'.$data->custID.' by User ID-'.Auth::user()->user_id.'.  File ID-'.$id);
        
        //  Delete from system if no longer in use
        Files::deleteFile($fileID);
        
        return response()->json(['success' => true]);
    }
}

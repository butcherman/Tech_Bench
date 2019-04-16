<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Customers;
use App\CustomerFiles;
use App\CustomerFileTypes;
use App\Files;

use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Illuminate\Http\UploadedFile;

class CustomerFilesController extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Load form to create a new file
    public function create()
    {
        $fileTypes = CustomerFileTypes::all();
        $fTypes = [];
        foreach($fileTypes as $type)
        {
            $fTypes[$type->file_type_id] = $type->description;
        }
        
        return view('customer.form.newFile', [
            'fileTypes' => $fTypes
        ]);
    }

    //  Store the new file
    public function store(Request $request)
    {
        $request->validate([
            'custID' => 'required|numeric',
            'name'   => 'required',
            'type'   => 'required',
            'file'   => 'required'
        ]);
        
        //  Set file locationi and clean filename for duplicates
        $filePath = config('filesystems.paths.customers').DIRECTORY_SEPARATOR.$request->custID;

        // create the file receiver
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));
        
        // check if the upload is success, throw exception or return response you need
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
        
        // receive the file
        $save = $receiver->receive();
        // check if the upload has finished (in chunk mode it will send smaller files)
        if ($save->isFinished()) {
        
        
            $fileID = $this->saveFile($save->getFile(), $filePath);

            //  Input the file into the customer files table
            CustomerFiles::create([
                'file_id'      => $fileID,
                'file_type_id' => $request->type,
                'cust_id'      => $request->custID,
                'user_id'      => Auth::user()->user_id,
                'name'         => $request->name
            ]);

            Log::info('File Added For Customer ID-'.$request->custID.' by User ID-'.Auth::user()->user_id.'.  New File ID-'.$fileID);
        }
        
        $handler = $save->handler();
        
        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true
        ]);
    }
    
    public function saveFile(UploadedFile $file, $filePath)
    {
        $fileName = Files::cleanFileName($filePath, $file->getClientOriginalName());
        
        //  Store the file
        $file->storeAs($filePath, $fileName);
        
        //  Put the file in the database
        $file = Files::create(
        [
            'file_name' => $fileName,
            'file_link' => $filePath.DIRECTORY_SEPARATOR
        ]);
        
        return $file->file_id;
    }

    //  Show customer files
    public function show($id)
    {
        $files = CustomerFiles::where('cust_id', $id)
            ->LeftJoin('customer_file_types', 'customer_files.file_type_id', '=', 'customer_file_types.file_type_id')
            ->LeftJoin('users', 'customer_files.user_id', '=', 'users.user_id')
            ->Join('files', 'customer_files.file_id', '=', 'files.file_id')
            ->orderBy('customer_files.file_type_id', 'asc')
            ->get();
        
        return view('customer.files', [
            'files' => $files
        ]);
    }

    //  Edit a customer file
    public function edit($id)
    {
        $custFile  = CustomerFiles::find($id);
        $fileTypes = CustomerFileTypes::all();
        $fTypes    = [];
        foreach($fileTypes as $type)
        {
            $fTypes[$type->file_type_id] = $type->description;
        }
        
        return view('customer.form.editFile', [
            'file'      => $custFile,
            'fileTypes' => $fTypes,
            'fileID'    => $id
        ]);
    }

    //  Update a customer file
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'           => 'required',
            'file_type_id'   => 'required'
        ]);
        
        CustomerFiles::find($id)->update([
            'name'         => $request->name,
            'file_type_id' => $request->file_type_id
        ]);
        
        Log::info('File ID-'.$id.' Updated For Customer by User ID-'.Auth::user()->user_id);
    }

    //  Remove a customer file
    public function destroy($id)
    {
        //  Remove the file from SystemFiles table
        $data = CustomerFiles::find($id);
        $fileID = $data->file_id;
        $data->delete();
        
        Log::info('File Deleted For Customer ID-'.$data->custID.' by User ID-'.Auth::user()->user_id.'.  File ID-'.$id);
        
        //  Delete from system if no longer in use
        Files::deleteFile($fileID);
    }
}

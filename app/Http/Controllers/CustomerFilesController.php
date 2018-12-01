<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Customers;
use App\CustomerFiles;
use App\CustomerFileTypes;
use App\Files;

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
        $filePath = config('filesystem.customers').DIRECTORY_SEPARATOR.$request->custID;
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
        
        Log::info('File Added For Customer', ['cust_id' => $request->custID, 'file_id' => $fileID, 'user_id' => Auth::user()->user_id]);
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
        $custFile = CustomerFiles::find($id);
        $fileTypes = CustomerFileTypes::all();
        $fTypes = [];
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
        
        Log::info('File Updated For Customer', ['file_id' => $id, 'user_id' => Auth::user()->user_id]);
    }

    //  Remove a customer file
    public function destroy($id)
    {
        //  Remove the file from SystemFiles table
        $data = CustomerFiles::find($id);
        $fileID = $data->file_id;
        $data->delete();
        
        Log::info('File Deleted For Customer', ['cust_id' => $data->custID, 'file_id' => $id, 'user_id' => Auth::user()->user_id]);
        
        //  Delete from system if no longer in use
        Files::deleteFile($fileID);
    }
}

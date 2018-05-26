<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Files;
use App\SystemTypes;
use App\SystemFiles;
use App\SystemFileTypes;

class SystemFilesController extends Controller
{

    
    public function index()
    {
        return view('system.form.newFile');
    }
    
    //  Ajax load to load the file for the system
    public function loadFiles($sysName, $fileType)
    {
        $fileList = SystemFiles::whereHas('SystemTypes', function($q) use($sysName)
        {
            $q->where('name', str_replace('-', ' ', $sysName));
        })->whereHas('SystemFileTypes', function($q) use($fileType)
        {
            $q->where('description', str_replace('-', ' ', $fileType));          
        })->with('user')->with('files')->get();
        
        return view('system.fileList', [
            'fileList' => $fileList
        ]);
    }

    //  Form to create a new system file
    public function create()
    {
        return view('system.form.newFile');
    }

    //  Store the new system file
    public function store(Request $request)
    {
        //  Validate incoming data
        $request->validate(['name' => 'required', 'file' => 'required', 'fileType' => 'required', 'system' => 'required']);
        
        //  Set file location and clean filename for duplicates
        $filePath = env('SYS_FOLDER')
            .DIRECTORY_SEPARATOR.$request['category']
            .DIRECTORY_SEPARATOR.$request['system'];
        
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
        $typeID = SystemFileTypes::where('description', $request['fileType'])->first()->type_id;
        $sysID  = SystemTypes::where('name', $request['system'])->first()->sys_id;
        
        //  Attach file to system type
        SystemFiles::create(
        [
            'sys_id'      => $sysID,
            'type_id'     => $typeID,
            'file_id'     => $fileID,
            'name'        => $request['name'],
            'description' => $request['description'],
            'user_id'     => \Auth::user()->user_id
        ]);
    }

    //  Form to show a file to edit
    public function edit($id)
    {
        $data = SystemFiles::find($id);

        return view('system.form.editFile', [
            'data' => $data
        ]);
    }

    //  Submit the edited file
    public function update(Request $request, $id)
    {
        SystemFiles::find($id)->update([
            'name'        => $request['name'],
            'description' => $request['description']
        ]);
    }
    
    //  Load the replace file form
    public function replaceForm($id)
    {
        return view('system.form.replaceFile', [
            'fileID' => $id
        ]);
    }
    
    //  Submit the replace file form
    public function replace($id, Request $request)
    {
        $request->validate(['file' => 'required']);
        
        //  Get the original file information
        $origData = SystemFiles::find($id)->with('files')->first();
        $filePath = $origData->files->file_link;
        $orgID    = $origData->file_id;
        //  Clean the filename
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
        
        SystemFiles::find($id)->update([
            'file_id' => $fileID
        ]);        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //  Remove the file from SystemFiles table
        $data = SystemFiles::find($id);
        $fileID = $data->file_id;
        $data->delete();
        
        //  Delete from system if no longer in use
        Files::deleteFile($fileID);
    }
}

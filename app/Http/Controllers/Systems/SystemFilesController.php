<?php

namespace App\Http\Controllers\Systems;

use App\Files;
use App\SystemTypes;
use App\SystemFiles;
use App\SystemFileTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SystemFilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //  Store a new system file
    public function store(Request $request)
    {
        //  Validate the form
        $request->validate([
            'name'  => 'required',
            'sysID' => 'required',
            'type'  => 'required',
            'file'  => 'required'
        ]);
        
        //  Get the system information
        $sys = SystemTypes::where('sys_id', $request->sysID)
            ->join('system_categories', 'system_types.cat_id', '=', 'system_categories.cat_id')
            ->select('system_types.*', 'system_categories.name AS cat_name')
            ->first();
                
        //  Set the file location and clean the file name for storage
        $filePath = config('filesystems.paths.systems')
            .DIRECTORY_SEPARATOR.strtolower($sys->cat_name)
            .DIRECTORY_SEPARATOR.$sys->folder_location;
        
        $fileName = Files::cleanFileName($filePath, $request->file->getClientOriginalName());
        
        //  Store the file and place it in the database
        $request->file->storeAs($filePath, $fileName);
        $file = Files::create([
            'file_name' => $fileName,
            'file_link' => $filePath.DIRECTORY_SEPARATOR
        ]);
        
        //  Get the information for the system files table
        $fileID = $file->file_id;
        $typeID = SystemFileTypes::where('description', $request->type)->first()->type_id;
        
        //  Attach file to system type
        SystemFiles::create([
            'sys_id'      => $request->sysID,
            'type_id'     => $typeID,
            'file_id'     => $fileID,
            'name'        => $request->name,
            'description' => $request->description,
            'user_id'     => Auth::user()->user_id
        ]);
        
        Log::info('File ID-'.$fileID.' Added For System ID-'.$request->sysID.' By User ID-'.Auth::user()->user_id);
        
        return response()->json(['success' => true]);
    }

    //  Return JSON array of the system files
    public function show($id)
    {
        $fileTypes = SystemFileTypes::all();        
        $files = SystemFiles::where('sys_id', $id)
            ->join('files', 'system_files.file_id', '=', 'files.file_id')
            ->join('users', 'system_files.user_id', '=', 'users.user_id')
            ->get();
        
        $i = 0;
        $fileArr = [];
        foreach($fileTypes as $type)
        {
            $fileArr[$i] = [
                'description' => $type->description,
                'data'        => false
            ];
                
            foreach($files as $file)
            {
                if($file->type_id == $type->type_id)
                {
                    $fileArr[$i]['data'][] = [
                        'id'          => $file->sys_file_id,
                        'url'         => route('download', [$file->file_id, $file->file_name]),
                        'name'        => $file->name,
                        'description' => $file->description,
                        'user'        => $file->first_name.' '.$file->last_name,
                        'added'       => date('M d, Y', strtotime($file->created_at))
                    ];
                }
            }
            
            $i++;
        }

        return response()->json($fileArr);
    }

    //  Update the file information
    public function update(Request $request, $id)
    {
        SystemFiles::find($id)->update([
            'name'        => $request->name,
            'description' => $request->desc
        ]);
        
        Log::info('File Information on System File ID-'.$id.' Updated by User ID-'.Auth::user()->user_id);
        
        return response()->json(['success' => true]);
    }
    
    //  Replace the file with a new one
    public function replace(Request $request)
    {
        //  Validate the form
        $request->validate(['file' => 'required']);
        
        //  Get the original file information
        $origData = SystemFiles::find($request->fileID)->with('files')->first();
        $filePath = $origData->files->file_link;
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
        
        SystemFiles::find($request->fileID)->update([
            'file_id' => $fileID
        ]);   
        
        Log::info('System File ID-'.$fileID.' Replaced by User ID-'.Auth::user()->user_id);
        
        return response()->json(['success' => true]);
    }

    //  Delete the system file
    public function destroy($id)
    {
        //  Remove the file from SystemFiles table
        $data = SystemFiles::find($id);
        $fileID = $data->file_id;
        
        Log::info('System File ID-'.$fileID.' Deleted For System ID-'.$data->sys_id.' by User ID-'.Auth::user()->user_id);
        
        $data->delete();
        
        //  Delete from system if no longer in use
        Files::deleteFile($fileID);
        
        return response()->json(['success' => true]);
    }
}

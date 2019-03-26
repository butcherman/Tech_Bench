<?php

namespace App\Http\Controllers\FileLinks;

use App\Files;
use App\FileLinks;
use App\FileLinkFiles;
use App\CustomerFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LinkFilesController extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Get the files for the link    
    public function getIndex($id, $dir)
    {
        switch($dir)
        {
            //  For files that are available for a guest to download
            case 'down':
                $files = FileLinkFiles::where('link_id', $id)
                    ->where('upload', false)
                    ->join('files', 'file_link_files.file_id', '=', 'files.file_id')
                    ->get();
                break;
            //  For files that are uploaded by a guest
            case 'up':
                $files = Files::where('file_link_files.link_id', $id)
                    ->where('file_link_files.upload', true)
                    ->join('file_link_files', 'files.file_id', '=', 'file_link_files.file_id')
                    ->with('FileLinkNotes')
                    ->get();
                break;
        }
        
        //  Add the download link to the collection
        foreach($files as $file)
        {
            $file->url       = route('download', [$file->file_id, $file->file_name]);
            $file->timestamp = date('M d, Y', strtotime($file->created_at));
        }
        
        return response()->json($files);
    }
    
    //  Add a file to the link
    public function postIndex(Request $request, $linkID, $dir)
    {
        //  If there are any files, process them
        if(!empty($request->file))
        {
            $filePath = config('filesystems.paths.links').DIRECTORY_SEPARATOR.$linkID;
            foreach($request->file as $file)
            {
                //  Clean the file and store it
                $fileName = Files::cleanFilename($filePath, $file->getClientOriginalName());
                $file->storeAs($filePath, $fileName);
                
                //  Place file in Files table of DB
                $newFile = Files::create([
                    'file_name' => $fileName,
                    'file_link' => $filePath.DIRECTORY_SEPARATOR
                ]);
                $fileID = $newFile->file_id;
                
                //  Place the file in the file link files table of DB
                FileLinkFiles::create([
                    'link_id'  => $linkID,
                    'file_id'  => $fileID,
                    'user_id'  => Auth::user()->user_id,
                    'upload'   => 0
                ]);
                
                //  Log stored file
                Log::info('File Stored', ['file_id' => $fileID, 'file_path' => $filePath.DIRECTORY_SEPARATOR.$fileName]);
            }
        }

        return response()->json(['success' => true]);
    }
    
    //  Move a file to the customer attached to the link
    public function moveFile(Request $request, $id)
    {
        $request->validate([
            'selected' => 'required',
            'file_id'  => 'required'
        ]);
        
        $linkData = FileLinks::find($id);
        
        $newPath = config('filesystems.paths.customers').DIRECTORY_SEPARATOR.$linkData->cust_id.DIRECTORY_SEPARATOR;
        $fileData = Files::find($request->file_id);
        
        //  Verify the file does not already exist in the customer data or file
        $dup = CustomerFiles::where('file_id', $request->file_id)->where('cust_id', $linkData->cust_id)->count();
        if($dup || Storage::exists($newPath.$fileData->file_name))
        {
            return response()->json(['success' => 'duplicate']);
        }
        
        //  Move the file to the customers file folder
        Storage::move($fileData->file_link.$fileData->file_name, $newPath.$fileData->file_name);
        
        //  Update file database
        $fileData->update([
            'file_link' => $newPath
        ]);
        
        //  Place file in customer database
        CustomerFiles::create([
            'file_id'      => $request->file_id,
            'file_type_id' => $request->selected,
            'cust_id'      => $linkData->cust_id,
            'user_id'      => Auth::user()->user_id,
            'name'         => $request->file_name
        ]);
        
        return response()->json(['success' => true]);
    }
    
    //  Delete a file attached to a link
    public function delFile($id)
    {
        $fileData = FileLinkFiles::find($id);
        $fileID   = $fileData->file_id;
        $fileData->delete();
        
        Files::deleteFile($fileID);
        
        return response()->json(['success' => true]);
    }
}

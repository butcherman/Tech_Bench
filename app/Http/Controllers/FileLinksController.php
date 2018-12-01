<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Files;
use App\FileLinks;
use App\FileLinkFiles;
use App\FileLinkNotes;

class FileLinksController extends Controller
{
    //  Only authorized users have access
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Landing page
    public function index()
    {
        return view('links.index');
    }

    //  Create a new File Link
    public function create()
    {
        return view('links.form.newLink');
    }

    //  Store the new file link
    public function store(Request $request)
    {
        $request->validate = ['name' => 'required', 'expire' => 'required'];
        
        //  Generate a random hash to use as the file link and make sure it is not already in use
        do
        {
            $hash = strtolower(str_random(15));
            $dup = FileLinks::where('link_hash', $hash)->get()->count();
        }while($dup != 0);
        
        //  Create the new file link
        $link = FileLinks::create([
            'user_id'      => Auth::user()->user_id,
            'link_hash'    => $hash,
            'link_name'    => $request->name,
            'expire'       => $request->expire,
            'allow_upload' => isset($request->allowUp) && $request->allowUp ? true : false
        ]);
        $linkID = $link->link_id;
        
        //  If there are any files, process them
        if(!empty($request->file))
        {
            $filePath = config('filesystem.paths.links').DIRECTORY_SEPARATOR.$linkID;
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
        
        Log::info('File Link Created', ['link_id' => $linkID, 'user_id' => Auth::user()->user_id]);
        
        return $linkID;
    }

    //  Show file links for a specific user
    public function show($id)
    {
        $links = FileLinks::where('user_id', $id)
            ->withCount('FileLinkFiles')
            ->orderBy('expire', 'desc')
            ->get();
        
        return view('links.loadLinks', [
            'links' => $links
        ]);
    }
    
    //  Show a links information
    public function details($id, $name)
    {
        $linkData = FileLinks::find($id);
        
        if(empty($linkData))
        {
            return view('links.badLink');
        }
        
        return view('links.details', [
            'data' => $linkData
        ]);
    }
    
    //  Get the files for the link
    public function getFiles($type, $linkID)
    {
        switch($type)
        {
            case 'down':
                $files = FileLInkFiles::where('link_id', $linkID)
                    ->where('upload', false)
                    ->join('files', 'file_link_files.file_id', '=', 'files.file_id')
                    ->get();
                break;
            case 'up':
                $files = Files::where('file_link_files.link_id', $linkID)
                    ->where('file_link_files.upload', true)
                    ->join('file_link_files', 'files.file_id', '=', 'file_link_files.file_id')
                    ->with('FileLinkNotes')
                    ->get();
                break;
        }
        
        return view('links.fileList', [
            'files' => $files,
            'type'  => $type
        ]);
    }
    
    //  Add a new file
    public function addFileForm($id)
    {
        return view('links.form.addFile', [
            'id' => $id
        ]);
    }
    
    //  Submit the additional files
    public function submitAddFile($id, Request $request)
    {
        $filePath = config('filesystems.paths.links').DIRECTORY_SEPARATOR.$id;
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
                'link_id'  => $id,
                'file_id'  => $fileID,
                'user_id'  => Auth::user()->user_id,
                'upload'   => 0
            ]);
            
            //  Log stored file
            Log::info('File Stored', ['file_id' => $fileID, 'file_path' => $filePath.DIRECTORY_SEPARATOR.$fileName]);
        }
    }
    
    //  Get a note that is attached to a file
    public function getNote($id)
    {
        $note = FileLinkNotes::find($id);
        
        return $note->note;
    }

    //  Edit a links basic informaiton
    public function edit($id)
    {
        $linkData = FileLinks::find($id);
        
        return view('links.form.editLink', [
            'data' => $linkData
        ]);
    }

    //  Submit the edit link form
    public function update(Request $request, $id)
    {
        $request->validate = ['link_name' => 'required', 'expire' => 'required'];
        
        FileLinks::find($id)->update([
            'link_name'    => $request->link_name,
            'expire'       => $request->expire,
            'allow_upload' => isset($request->allowUp) && $request->allowUp ? true : false
        ]);
        
        Log::info('File Link Updated', ['link_id' => $id]);
    }
    
    //  Delete a file attached to a link
    public function deleteLinkFile($linkFileID)
    {
        $fileData = FileLinkFiles::find($linkFileID);
        $fileID = $fileData->file_id;
        $fileData->delete();
        
        Files::deleteFile($fileID);
    }

    //  Delete a file link
    public function destroy($id)
    {
        //  Remove the file from database
        $data = FileLinkFiles::where('link_id', $id)->get();
        if(!$data->isEmpty())
        {
            foreach($data as $file)
            {
                $fileID = $file->file_id;
                $file->delete();

                //  Delete the file if it is no longer in use
                Files::deleteFile($fileID);
            }
        }
        
        $link = FileLinks::find($id)->delete();
        
        Log::info('File link deleted', ['link_id' => $id, 'user_id' => Auth::user()->user_id]);
    }
}

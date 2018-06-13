<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Files;
use App\FileLinks;
use App\FileLinkFiles;
use App\FileLinkNotes;

class UserLinksController extends Controller
{
    public function index()
    {
        echo 'file link home';
    }
    
    public function details($id)
    {
        $details = FileLinks::where('link_hash', $id)->first();
        
        
        
        
        
        $files = FileLinkFiles::where('link_id', $details->link_id)
            ->where('upload', false)
            ->join('files', 'file_link_files.file_id', '=', 'files.file_id')
            ->get();
 
        return view('links.guest.details', [
            'details' => $details,
            'files' => $files,
            'allowUp' => $details->allow_upload
        ]);
    }
    
    
    public function uploadFiles($hash, Request $request)
    {
        $request->validate = ['name' => 'required', 'file' => 'required'];
            
        $details = FileLinks::where('link_hash', $hash)->first();
        
        $filePath = env('LINK_FOLDER').DIRECTORY_SEPARATOR.$details->link_id;

        foreach($request->file as $file)
        {
            echo 'file ';
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
                'link_id' => $details->link_id,
                'file_id' => $fileID,
                'added_by' => $request->name,
                'upload' => 1
            ]);
            
            if(!empty($request->note))
            {
                FileLinkNotes::create([
                    'link_id' => $details->link_id,
                    'file_id' => $fileID,
                    'note' => $request->note
                ]);
            }
        }
        
        return $request->all();
    }
}

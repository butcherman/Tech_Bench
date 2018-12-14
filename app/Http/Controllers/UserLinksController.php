<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Files;
use App\FileLinks;
use App\FileLinkFiles;
use App\FileLinkNotes;
use App\User;
use App\Notifications\NewFileUploaded;

class UserLinksController extends Controller
{
    public function index()
    {
        echo 'file link home';
    }
    
    public function details($id)
    {
        $details = FileLinks::where('link_hash', $id)->first();
        
        //  Verify that the link is valid
        if(empty($details))
        {
            return view('links.guest.badLink');
        }
        //  Verify that the link has not expired
        else if($details->expire <= date('Y-m-d'))
        {
            return view('links.guest.expiredLink');
        }
        
        $files = FileLinkFiles::where('link_id', $details->link_id)
            ->where('upload', false)
            ->join('files', 'file_link_files.file_id', '=', 'files.file_id')
            ->get();
        
        return view('links.guest.details', [
            'details' => $details,
            'files'   => $files,
            'allowUp' => $details->allow_upload
        ]);
    }
    
    public function uploadFiles($hash, Request $request)
    {
        $request->validate(['name' => 'required', 'file' => 'required']);
            
        $details = FileLinks::where('link_hash', $hash)->first();
        
        $filePath = config('filesystems.paths.links').DIRECTORY_SEPARATOR.$details->link_id;

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
                'link_id'  => $details->link_id,
                'file_id'  => $fileID,
                'added_by' => $request->name,
                'upload'   => 1
            ]);
            
            if(!empty($request->note))
            {
                FileLinkNotes::create([
                    'link_id' => $details->link_id,
                    'file_id' => $fileID,
                    'note'    => $request->note
                ]);
            }
        }
        
        //  Send email and notification to the creator of the link
        $user = User::find($details->user_id);
        Notification::send($user, new NewFileUploaded($details));
        
        return $request->all();
    }
}
 
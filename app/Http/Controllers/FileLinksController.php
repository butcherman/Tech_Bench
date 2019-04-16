<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Zip;
use App\Files;
use App\FileLinks;
use App\FileLinkFiles;
use App\FileLinkNotes;

use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Illuminate\Http\UploadedFile;

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
        $request->validate(['name' => 'required', 'expire' => 'required']);
        
        if(!empty($request->file))
        {
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

                $fileID =  $this->newLinkFile($save->getFile(), $linkID);

                //  Place the file in the file link files table of DB
                FileLinkFiles::create([
                    'link_id'  => $linkID,
                    'file_id'  => $fileID,
                    'user_id'  => Auth::user()->user_id,
                    'upload'   => 0
                ]);

                return 'this is the link id'.$linkID;
            }
            // we are in chunk mode, lets send the current progress
            /** @var AbstractHandler $handler */
            $handler = $save->handler();

            return response()->json([
                "done" => $handler->getPercentageDone(),
                'status' => true
            ]);
        }
        else
        {
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
            
            return $linkID;
        }
    }
    
    public function newLinkFile(UploadedFile $file, $linkID)
    {
        $filePath = config('filesystems.paths.links').DIRECTORY_SEPARATOR.$linkID;
        
        //  Clean the file and store it
        $fileName = Files::cleanFilename($filePath, $file->getClientOriginalName());
        $file->storeAs($filePath, $fileName);

        //  Place file in Files table of DB
        $newFile = Files::create([
            'file_name' => $fileName,
            'file_link' => $filePath.DIRECTORY_SEPARATOR
        ]);
        $fileID = $newFile->file_id;

        //  Log stored file
        Log::info('File Stored', ['file_id' => $fileID, 'file_path' => $filePath.DIRECTORY_SEPARATOR.$fileName]);
        
        return $fileID;
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
    public function details($id, $name, Request $request)
    {
        $linkData = FileLinks::find($id);
        if(!$request->session()->has('newLinkId'))
        {
            $request->session()->forget('newLinkId');
        }
        
        if(empty($linkData))
        {
            return view('links.badLink');
        }
        
        $emailMsg = "mailto:?subject=A File Link Has Been Created For You&body=View the link details here:  ".route('userLink.details', ['link' => $linkData->link_hash]);
        
        return view('links.details', [
            'data' => $linkData,
            'emMsg' => $emailMsg
        ]);
    }
    
    //  Get the files for the link
    public function getFiles($type, $linkID)
    {
        $files = null;
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
            'files'  => $files,
            'type'   => $type,
            'linkID' => $linkID
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
            return $this->saveFile($save->getFile(), $id);
        }
        // we are in chunk mode, lets send the current progress
        /** @var AbstractHandler $handler */
        $handler = $save->handler();
        
        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true
        ]);
    }
    
    public function saveFile(UploadedFile $file, $id)
    {
        $filePath = config('filesystems.paths.links').DIRECTORY_SEPARATOR.$id;
        
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
        
        return $fileID;
    }
    
    protected function createFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace('.'.$extension, '', $file->getClientOriginalName());
        
        $filename .= '_'.md5(time()).'.'.$extension;
        
        return $filename;
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
    
    //  Download all files uploaded to a link
    public function downloadAllFiles($linkID)
    {
        $files = Files::where('file_link_files.link_id', $linkID)
            ->where('file_link_files.upload', true)
            ->join('file_link_files', 'files.file_id', '=', 'file_link_files.file_id')
            ->with('FileLinkNotes')
            ->get();
        $path = config('filesystems.disks.local.root').DIRECTORY_SEPARATOR;
        
        $zip = Zip::create($path.'download.zip');
        foreach($files as $file)
        {
            $zip->add($path.$file->file_link.$file->file_name);
        }
        
        $zip->close();
        
        return response()->download($path.'download.zip')->deleteFileAfterSend(true);        
    }

    //  Submit the edit link form
    public function update(Request $request, $id)
    {
        $request->validate([
            'link_name' => 'required', 
            'expire'    => 'required'
        ]);
        
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
        
        FileLinks::find($id)->delete();
        
        Log::info('File link deleted', ['link_id' => $id, 'user_id' => Auth::user()->user_id]);
    }
}

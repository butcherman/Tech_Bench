<?php

namespace App\Http\Controllers\FileLinks;

use App\Files;
use App\FileLinks;
use App\FileLinkFiles;
use App\CustomerFiles;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\FileLinksCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use App\http\Resources\FileLinkFilesCollection;

class LinkFilesController extends Controller
{
    public function __construct()
    {
        //  Verify the user is logged in and has permissions for this page
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            $this->authorize('hasAccess', 'use_file_links');
            return $next($request);
        });
    }

    //  Add a file to the file link
    public function store(Request $request)
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        //  Verify that the upload is valid and being processed
        if($receiver->isUploaded() === false)
        {
            Log::error('Upload File Missing - '.$request->toArray());
            throw new UploadMissingFileException();
        }

        //  Recieve and process the file
        $save = $receiver->receive();

        //  See if the uploade has finished
        if($save->isFinished())
        {
            $filePath = config('filesystems.paths.links').DIRECTORY_SEPARATOR.$request->linkID;
            $file = $save->getFile();

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
                'link_id'  => $request->linkID,
                'file_id'  => $fileID,
                'user_id'  => Auth::user()->user_id,
                'upload'   => 0
            ]);

            //  Log stored file
            Log::info('File Stored', ['file_id' => $fileID, 'file_path' => $filePath.DIRECTORY_SEPARATOR.$fileName]);

            return response()->json(['success' => true]); ;
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

    //  Show the files attached to a link
    public function show($id)
    {
        // $files = FileLinkFiles::where('file_link_files.link_id', $id)
        //     ->select('added_by', 'file_link_files.created_at', 'files.file_id', 'files.file_name', 'file_link_files.link_file_id', 'note', 'upload')
        //     ->join('files', 'file_link_files.file_id', '=', 'files.file_id')
        //     ->leftJoin('file_link_notes', 'file_link_files.file_id', '=', 'file_link_notes.file_id')
        //     ->orderBy('user_id', 'ASC')
        //     ->orderBy('file_link_files.created_at', 'ASC')
        //     ->get();

        // foreach($files as $file)
        // {
        //     $file->timestamp = date('M d, Y', strtotime($file->created_at));
        // }

        // Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        // Log::debug('File Data - ', $files->toArray());
        // return response()->json($files);

        // return 'files';

        $files = new FileLinkFilesCollection(
            FileLinkFiles::where('link_id', $id)
            ->orderBy('user_id', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->with('Files')
            ->with('User')
            ->get()
        );

        return $files;
    }

    //  Move a file to a customer file
    public function update(Request $request, $id)
    {
        $request->validate([
            'fileID'   => 'required',
            'fileName' => 'required',
            'fileType' => 'required'
        ]);

        $linkData = FileLinks::find($id);

        $newPath = config('filesystems.paths.customer').DIRECTORY_SEPARATOR.$linkData->cust_id.DIRECTORY_SEPARATOR;
        $fileData = Files::find($request->fileID);

        //  Verify that the file does not already exist in the customerdata or file
        $dup = CustomerFiles::where('file_id', $request->fileID)->where('cust_id', $linkData->cust_id)->count();
        if($dup || Storage::exists($newPath.$fileData->file_name))
        {
            return response()->json(['success' => 'duplicate']);
        }

        //  Move the file to the customrs file folder
        Storage::move($fileData->file_link.$fileData->file_name, $newPath.$fileData->file_name);

        //  Update the file path in the database
        $fileData->update([
            'file_link' => $newPath
        ]);

        //  Place the file in the customer database
        CustomerFiles::create([
            'file_id'      => $request->fileID,
            'file_type_id' => $request->fileType,
            'cust_id'      => $linkData->cust_id,
            'user_id'      => Auth::user()->user_id,
            'name'         => $request->fileName
        ]);

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('File Data - ', $request->toArray());
        Log::info('File ID-'.$request->fileId.' moved to customer ID-'.$linkData->cust_id.' for link ID-'.$id);
        return response()->json(['success' => true]);
    }

    //  Delete a file attached to a link
    public function destroy($id)
    {
        $fileData = FileLinkFiles::find($id);
        $fileID   = $fileData->file_id;
        $fileData->delete();

        Files::deleteFile($fileID);

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::info('File ID-'.$fileData->file_id.' deleted for Link ID-'.$fileData->link_id);
        return response()->json(['success' => true]);
    }
}

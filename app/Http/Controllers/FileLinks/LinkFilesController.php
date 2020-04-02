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
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\FileLinkFilesCollection;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;

class LinkFilesController extends Controller
{
    private $user;

    public function __construct()
    {
        //  Verify the user is logged in and has permissions for this page
        $this->middleware('auth');
        $this->middleware(function($request, $next) {
            $this->user = auth()->user();
            $this->authorize('hasAccess', 'Use File Links');
            return $next($request);
        });
    }

    //  Add a file to the file link
    public function store(Request $request)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data - ', $request->toArray());

        $request->validate([
            'linkID' => 'required|exists:file_links,link_id'
        ]);

        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

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

        Log::debug('File being uploaded.  Percentage done - '.$handler->getPercentageDone());
        return response()->json([
            'done'   => $handler->getPercentageDone(),
            'status' => true
        ]);
    }

    //  Show the files attached to a link
    public function show($id)
    {
        $files = new FileLinkFilesCollection(
            FileLinkFiles::where('link_id', $id)
            ->orderBy('user_id', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->with('Files')
            ->with('User')
            ->get()
        );

        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        return $files;
    }

    //  Move a file to a customer file
    public function update(Request $request, $id)
    {
        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name.'. Submitted Data - ', $request->toArray());

        $request->validate([
            'fileID'   => 'required',
            'fileName' => 'required',
            'fileType' => 'required'
        ]);

        $linkData = FileLinks::find($id);

        $newPath = config('filesystems.paths.customers').DIRECTORY_SEPARATOR.$linkData->cust_id.DIRECTORY_SEPARATOR;
        $fileData = Files::find($request->fileID);

        //  Verify that the file does not already exist in the customerdata or file
        $dup = CustomerFiles::where('file_id', $request->fileID)->where('cust_id', $linkData->cust_id)->count();
        if($dup || Storage::exists($newPath.$fileData->file_name))
        {
            return response()->json(['success' => 'false', 'reason' => 'This File Already Exists in Customer Files']);
        }

        //  Move the file to the customrs file folder
        try
        {
            Storage::move($fileData->file_link.$fileData->file_name, $newPath.$fileData->file_name);
        }
        catch(\Exception $e)
        {
            report($e);
            return response()->json(['success' => false, 'reason' => 'Cannot Find File']);
        }

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

        Log::debug('File Data - ', $request->toArray());
        Log::info('File ID-'.$request->fileId.' moved to customer ID-'.$linkData->cust_id.' for link ID-'.$id);
        return response()->json(['success' => true]);
    }

    //  Delete a file attached to a link
    public function destroy($id)
    {
        //  Get the necessary file information and delete it from the database
        $fileData = FileLinkFiles::find($id);
        $fileID   = $fileData->file_id;
        $fileData->delete();

        //  Delete the file from the folder (not, will not delete if in use elsewhere)
        Files::deleteFile($fileID);

        Log::debug('Route '.Route::currentRouteName().' visited by '.Auth::user()->full_name);
        Log::info('File ID-'.$fileData->file_id.' deleted for Link ID-'.$fileData->link_id.' by '.Auth::user()->user_id);
        return response()->json(['success' => true]);
    }
}

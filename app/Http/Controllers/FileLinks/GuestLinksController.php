<?php

namespace App\Http\Controllers\FileLinks;

use App\User;
use App\Files;
use App\FileLinks;
use App\FileLinkNotes;
use App\FileLinkFiles;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use App\Notifications\NewFileUpload;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Notification;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;

use App\http\Resources\FileLinkFilesCollection;


class GuestLinksController extends Controller
{
    //  Landing page if no link is sent
    public function index()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.\Request::ip());
        return view('links.guestIndex');
    }

    //  Show the link details for the user
    public function show($id)
    {
        $details = FileLinks::where('link_hash', $id)->first();

        //  Verify that the link is valid
        if(empty($details))
        {
            Log::warning('Visitor '.\Request::ip().' visited bad link Hash - '.$id);
            return view('links.guestBadLink');
        }
        //  Verify that the link has not expired
        else if($details->expire <= date('Y-m-d'))
        {
            Log::warning('Visitor '.\Request::ip().' visited expired link Hash - '.$id);
            return view('links.guestExpiredLink');
        }

        //  Link is valid - determine if the link has files that can be downloaded
        $files = FileLinkFiles::where('link_id', $details->link_id)
            ->where('upload', false)
            ->count();

        if($files == 0 && $details->allow_upload === 'No')
        {
            Log::warning('Visitor ' . \Request::ip() . ' visited a link that they cannot do anything with.  Hash - ' . $id);
            return view('links.guestDeadLink');
        }

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.\Request::ip());
        Log::debug('Link Hash-'.$id);
        return view('links.guestDetails', [
            'hash'    => $id,
            'details' => $details,
            'hasFiles' => $files > 0 ? true : false,
            'allowUp' => $details->allow_upload === 'Yes' ? true : false,
        ]);
    }

    //  Get the guest available files for the link
    public function getFiles($id)
    {
        $linkID = FileLinks::where('link_hash', $id)->first()->link_id;

        $files = new FileLinkFilesCollection(
            FileLinkFiles::where('link_id', $linkID)
                ->where('upload', 0)
                ->orderBy('created_at', 'ASC')
                ->with('Files')
                ->get()
        );

        return $files;
    }

    //  Upload new file
    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required', 'file' => 'required']);

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
            $this->saveFile($save->getFile(), $id, $request);

            return 'uploaded successfully';
        }

        //  Get the current progress
        $handler = $save->handler();

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.\Request::ip());
        Log::debug('File being uploaded.  Percentage done - '.$handler->getPercentageDone());
        return response()->json([
            'done'   => $handler->getPercentageDone(),
            'status' => true
        ]);
    }

    //  Save the file in the database
    private function saveFile(UploadedFile $file, $id, $request)
    {
        $details = FileLinks::where('link_hash', $id)->first();
        $filePath = config('filesystems.paths.links').DIRECTORY_SEPARATOR.$details->link_id;

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
            'upload'   => 1,
            'note'     => $request->note
        ]);

        Log::info('File uploaded by guest '.\Request::ip().' for file link -'.$details->link_id);
        Log::debug('File Data -', $request->toArray());

        return response()->json(['success' => true]);
    }

    //  Notify the owner of the link that files were uploaded
    public function notify(Request $request, $id)
    {
        $request->validate([
            '_complete' => 'required',
            'count'     => 'required|integer'
        ]);

        $details = FileLinks::where('link_hash', $id)->first();

        $user = User::find($details->user_id);
        Notification::send($user, new NewFileUpload($details));
    }
}
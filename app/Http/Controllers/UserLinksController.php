<?php

namespace App\Http\Controllers;

use Zip;
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
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Illuminate\Http\UploadedFile;

class UserLinksController extends Controller
{
    public function index()
    {
        return view('links.guest.deadLink');
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
            'hash'    => $id,
            'details' => $details,
            'files'   => $files,
            'allowUp' => $details->allow_upload
        ]);
    }

    //  Downalod all files available to user
    public function downloadAllFiles($hash)
    {
        $details = FileLinks::where('link_hash', $hash)->first();

        //  Verify that the link is valid
        if(empty($details))
        {
            return abort(404);
        }

        $files = FileLinkFiles::where('link_id', $details->link_id)
            ->where('upload', false)
            ->join('files', 'file_link_files.file_id', '=', 'files.file_id')
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

    public function uploadFiles($hash, Request $request)
    {
        $request->validate(['name' => 'required', 'file' => 'required']);

        // Log::debug('request data', $request->toArray());

        $details = FileLinks::where('link_hash', $hash)->first();

        $filePath = config('filesystems.paths.links').DIRECTORY_SEPARATOR.$details->link_id;

        // foreach($request->file as $file)
        // {
        //     //  Clean the file and store it
        //     $fileName = Files::cleanFilename($filePath, $file->getClientOriginalName());
        //     $file->storeAs($filePath, $fileName);

        //     //  Place file in Files table of DB
        //     $newFile = Files::create([
        //         'file_name' => $fileName,
        //         'file_link' => $filePath.DIRECTORY_SEPARATOR
        //     ]);
        //     $fileID = $newFile->file_id;

        //     //  Place the file in the file link files table of DB
        //     FileLinkFiles::create([
        //         'link_id'  => $details->link_id,
        //         'file_id'  => $fileID,
        //         'added_by' => $request->name,
        //         'upload'   => 1
        //     ]);

        //     if(!empty($request->note))
        //     {
        //         FileLinkNotes::create([
        //             'link_id' => $details->link_id,
        //             'file_id' => $fileID,
        //             'note'    => $request->note
        //         ]);
        //     }
        // }

        // //  Send email and notification to the creator of the link
        // $user = User::find($details->user_id);
        // Notification::send($user, new NewFileUploaded($details));

        // return $request->all();


        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if($receiver->isUploaded() === false)
        {
            throw new UploadMissingFileException();
        }

        $save = $receiver->receive();

        if($save->isFinished())
        {
            // $this->saveFile($save->getFile());
            $file = $save->getFile();

            $fileName = Files::cleanFileName($filePath, $file->getClientOriginalName());
            $file->storeAs($filePath, $fileName);

            $newFile = Files::create([
                'file_name' => $fileName,
                'file_link' => $filePath . DIRECTORY_SEPARATOR
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

            $user = User::find($details->user_id);
            Notification::send($user, new NewFileUploaded($details));

            return 'upload successful';
        }

        $handler = $save->handler();
        return response()->json([
            'done' => $handler->getPercentageDone(),
            'status' => true
        ]);
    }
}

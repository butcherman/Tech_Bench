<?php

namespace App\Http\Controllers\Home;

use App\Events\Home\UploadedFileEvent;
use App\Traits\FileTrait;
use App\Models\FileUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Home\UploadedFileRequest;

class UploadFileController extends Controller
{
    use FileTrait;

    /**
     * Handle an uploaded file
     */
    public function __invoke(UploadedFileRequest $request)
    {
        //  Process the individual file chunk
        $status = $this->getChunk($request);

        //  If done, save to the database
        if($status['complete'])
        {
            $newFile = FileUploads::create([
                'disk'      => $status['disk'],
                'folder'    => $status['folder'],
                'file_name' => $status['filename'],
                'public'    => filter_var($request->public, FILTER_VALIDATE_BOOL),  //  Force as boolean
            ]);

            //  Determine if other files have been uploaded by this request
            $fileArr = session('new-file-upload') !== null ? session('new-file-upload') : [];
            $fileArr[] = $newFile;

            session(['new-file-upload' => $fileArr]);
            event(new UploadedFileEvent($newFile));
        }

        return $status;
    }
}

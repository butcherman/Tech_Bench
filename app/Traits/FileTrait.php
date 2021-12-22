<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

use App\Models\FileUploads;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * File Trait assists with processing uploading and moving files
 */
trait FileTrait
{
    protected $disk;
    protected $folder;

    /**
     * Get an uploaded file chunk and process it
     */
    protected function getChunk($request)
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
        $save     = $receiver->receive();

        //  Save a completed upload
        if($save->isFinished())
        {
            $this->disk   = $request->disk;
            $this->folder = $request->folder;
            $filename     = $this->saveFile($save->getFile());

            $status = [
                'percent'  => 100,
                'complete' => true,
                'filename' => $filename,
                'disk'     => $request->disk,
                'folder'   => $request->folder,
            ];

            Log::debug('File Upload Completed.  Details - ', $status);
            return $status;
        }

        $handler = $save->handler();
        $status  = [
            'percent'  => $handler->getPercentageDone(),
            'complete' => false,
        ];

        Log::debug('File upload in progress.  Details - ', $status);
        return $status;
    }

    /**
     * Save the file to the file system
     */
    protected function saveFile(UploadedFile $file)
    {
        $properName = $this->cleanFilename($file->getClientOriginalName());
        $fileName   = $this->checkForDuplicate($properName);

        $file->storeAs($this->folder, $fileName, $this->disk);
        return $fileName;
    }

    /**
     * Sanitize the filename to remove any spaces or illegal characters
     */
    protected function cleanFilename($name)
    {
        $newName = str_replace(' ', '_', preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $name));

        return $newName;
    }

    /**
     * Check if the filename already exists.  If it does, append the filename with (number)
     */
    protected function checkForDuplicate($name)
    {

        if(Storage::disk($this->disk)->exists($this->folder.DIRECTORY_SEPARATOR.$name))
        {
            $parts = pathinfo($name);
            $ext   = isset($parts['extension']) ? ('.'.$parts['extension']) : '';
            $base  = $parts['filename'];
            $number = 0;


            do
            {
                $name = $base.'('.++$number.')'.$ext;
            } while(Storage::disk($this->disk)->exists($this->folder.DIRECTORY_SEPARATOR.$name));
        }

        return $name;
    }

    /**
     * Make sure that there is some file data in the users current session - abort if it is missing
     */
    protected function checkForFile()
    {
        if(!session()->has('new-file-upload'))
        {
            Log::critical('File upload information missing', [
                'route' => \Request::route()->getName(),
                'user'  => Auth::check() ? Auth::user()->username : \Request::ip(),
            ]);
            abort(500, 'Uploaded File Data Missing');
        }
    }

    /**
     * Move a file from one location to another and store new location in database
     */
    protected function moveStoredFile($fileId, $newFolder, $newDisk = null)
    {
        $file = FileUploads::find($fileId);

        $this->disk   = $newDisk !== null ? $newDisk : $file->disk;
        $this->folder = $newFolder;

        //  Verify file is not duplicate and move
        $newName = $this->checkForDuplicate($file->file_name);
        Storage::disk($this->disk)->move($file->folder.DIRECTORY_SEPARATOR.$file->file_name, $this->folder.DIRECTORY_SEPARATOR.$newName);

        //  Update the database
        $file->file_name = $newName;
        $file->folder    = $this->folder;
        $file->disk      = $this->disk;
        $file->save();

        return true;
    }

    /**
     * Determine if a file is no longer in use, and then delete it from the filesystem
     */
    protected function deleteFile($fileID)
    {
        $fileData = FileUploads::find($fileID);
        $file     = $fileData->only(['disk', 'folder', 'file_name']);

        Log::debug('Attempting to delete file', $fileData->toArray());

        //  Try to delete the file from the database, if it fails, the file is in use elsewhere
        try
        {
            $fileData->delete();
        }
        catch(QueryException $e)
        {
            Log::debug('File ID '.$fileID.' is still in use and cannot be deleted');
            return false;
        }

        //  Delete the file from file storage
        Log::alert('File '.$file['folder'].DIRECTORY_SEPARATOR.$file['file_name'].' has been deleted');
        Storage::disk($file['disk'])->delete($file['folder'].DIRECTORY_SEPARATOR.$file['file_name']);

        return true;
    }
}

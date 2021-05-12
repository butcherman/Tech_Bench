<?php

namespace App\Traits;

use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 *  FileTrait will process all uploaded files
 */
trait FileTrait
{
    protected $disk;
    protected $folder;

    //  Get an uploaded file chunk
    protected function getChunk($request, $disk = 'local', $folder = null)
    {
        $this->disk   = $disk;
        $this->folder = $folder;

        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
        $save     = $receiver->receive();

        if($save->isFinished())
        {
            $filename = $this->saveFile($save->getFile());
            $status   = [
                'done'     => 100,
                'status'   => true,
                'filename' => $filename,
            ];
            Log::info('File upload completed', $status);

            return $status;
        }

        $handler = $save->handler();
        $status  = [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];

        Log::debug('File chunk received - ', $status);
        return $status;
    }

    //  Save the file to the storage system
    protected function saveFile(UploadedFile $file)
    {
        $properName = $this->cleanFilename($file->getClientOriginalName());
        $fileName   = $this->checkForDuplicate($properName);

        Log::info('Saving uploaded file', ['name' => $fileName, 'disk' => $this->disk, 'location' => $this->folder]);
        $file->storeAs($this->folder, $fileName, $this->disk);
        return $fileName;
    }

    //  Sanitize the filename to remove any illegal characters and spaces
    protected function cleanFilename($name)
    {
        $newName =  str_replace(' ', '_', preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $name));
        Log::debug('Cleaned Filename', ['original_name' => $name, 'clean_name' => $newName]);

        return $newName;
    }

    //  Check to see if the file already exists, if so append filename as needed
    protected function checkForDuplicate($name)
    {
        Log::debug('Checking File '.$name.' to see if it already exists on '.$this->disk.' in the '.$this->folder.' folder');

        if(Storage::disk($this->disk)->exists($this->folder.DIRECTORY_SEPARATOR.$name))
        {
            $parts = pathinfo($name);
            $ext   = isset($parts['extension']) ? ('.'.$parts['extension']) : '';
            $base  = $parts['filename'];
            $number = 0;

            Log::debug('File exists - updating name '.$base);

            do
            {
                $name = $base.'('.++$number.')'.$ext;
                Log::debug('Checking to see if updated name exists - '.$name);
            } while(Storage::disk($this->disk)->exists($this->folder.DIRECTORY_SEPARATOR.$name));
        }

        Log::debug('Resulting filename - '.$name);
        return $name;
    }
}

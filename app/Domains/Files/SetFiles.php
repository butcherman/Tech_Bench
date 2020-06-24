<?php

namespace App\Domains\Files;

use App\Files;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;


class SetFiles
{
    // protected $receiver;
    protected $path, $disk;

    public function __construct()
    {
        $this->path = config('filesystems.paths.default');
        $this->disk = 'local';
    }

    protected function getChunk($request)
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
        $save = $receiver->receive();

        if($save->isFinished())
        {
            return $this->saveFile($save->getFile());
        }

        return false;
    }

    protected function saveFile(UploadedFile $file)
    {
        $fileName = $this->cleanFilename($file->getClientOriginalName());
        $fileName = $this->checkForDuplicate($fileName);

        $file->storeAs($this->path, $fileName);
        $user = isset(Auth::user()->full_name) ? Auth::user()->full_name : \Request::ip();
        Log::info('New file '.$fileName.' stored in location - '.$this->path.' by '.$user);

        return $fileName;
    }

    protected function deleteFile($fileID)
    {
        $fileData = Files::find($fileID);
        $fileLink = $fileData->file_link.$fileData->file_name;

        try
        {
            //  Try to delete file from database - will throw error if foreign key is in use
            $fileData->delete();
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            //  Unable to remove file from the database
            Log::warning('Attempt to delete file failed.  Reason - '.$e.'. Additional File Data - ', ['file_id' => $fileID, 'file_name' => $fileLink]);
            return false;
        }

        //  Delete the file from the storage system
        Storage::delete($fileLink);

        Log::notice('File deleted. File Information - ', ['file_id' => $fileID, 'file_name' => $fileLink]);
        return true;
    }

    protected function addDatabaseRow($filename, $path)
    {
        $file = Files::create([
            'file_name' => $filename,
            'file_link' => $path.DIRECTORY_SEPARATOR,
        ]);

        return $file->file_id;
    }

    //  Sanitize the filename to remove any illegal characters
    protected function cleanFilename($name)
    {
        return preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $name);
    }

    //  Check to see if the file already exists, if so append filename as needed
    protected function checkForDuplicate($name)
    {
        if(Storage::disk($this->disk)->exists($this->path.DIRECTORY_SEPARATOR.$name))
        {
            $parts = pathinfo($name);
            $ext   = isset($parts['extension']) ? ('.'.$parts['extension']) : '';
            $base = $parts['filename'];
             $number = 0;

            do
            {
                $name = $base.'('.++$number.')'.$ext;
            } while(Storage::disk($this->disk)->exists($this->path.DIRECTORY_SEPARATOR.$name));
        }

        return $name;
    }
}

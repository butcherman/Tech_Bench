<?php

namespace App\Domains;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FilesDomain
{
    protected $path, $disk;

    public function __construct($path = null, $disk = 'local')
    {
        $this->path = $path === null ? config('filesystems.paths.default') : $path;
        $this->disk = $disk;
    }

    public function saveFile(UploadedFile $file)
    {
        $fileName = $this->cleanFileName($file->getClientOriginalName());
        $fileName = $this->checkForDuplicate($fileName);
        $file->storeAs($this->path, $fileName, $this->disk);

        Log::debug('New File stored.  Details - ', ['disk' => $this->disk, 'path' => $this->path, 'filename' => $fileName]);
        return $fileName;
    }

    //  Sanitize the filename to remove any illegal characters
    protected function cleanFileName($name)
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

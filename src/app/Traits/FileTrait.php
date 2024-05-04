<?php

namespace App\Traits;

use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

trait FileTrait
{
    protected $disk;

    protected $folder;

    protected $isPublic;

    /**
     * Set the Disk and Folder to be used
     */
    protected function setFileData($disk, $folder, $isPublic = false)
    {
        $this->disk = $disk;
        $this->folder = $folder;
        $this->isPublic = $isPublic;
    }

    /**
     * Capture a chunk of a file upload and process it
     */
    protected function getChunk(Request $request)
    {
        $receiver = new FileReceiver(
            'file',
            $request,
            HandlerFactory::classFromRequest($request)
        );
        $save = $receiver->receive();

        //  Save a completed upload
        if ($save->isFinished()) {
            $savedFile = $this->saveFile($save->getFile());

            Log::info('File Upload Completed', $savedFile->toArray());

            return $savedFile;
        }

        // @codeCoverageIgnoreStart
        $handler = $save->handler();
        $status = [
            'percent' => $handler->getPercentageDone(),
            'complete' => false,
        ];

        Log::debug('File upload in progress.  Details - ', $status);

        return false;
        // @codeCoverageIgnoreEnd
    }

    /**
     * Save the file and create database record
     */
    protected function saveFile(UploadedFile $file)
    {
        $fileName = $this->saveFileToDisk($file);

        return FileUpload::create([
            'disk' => $this->disk,
            'folder' => $this->folder,
            'file_name' => $fileName,
            'file_size' => $file->getSize(),
            'public' => $this->isPublic,
        ]);
    }

    /**
     * Process the file and save to proper folder
     */
    protected function saveFileToDisk(UploadedFile $file)
    {
        $properName = $this->cleanFilename($file->getClientOriginalName());
        $fileName = $this->checkForDuplicate($properName);

        $file->storeAs($this->folder, $fileName, $this->disk);

        return $fileName;
    }

    /**
     * Sanitize the filename to remove any spaces or illegal characters
     */
    protected function cleanFilename($name)
    {
        $newName = str_replace(
            ' ',
            '_',
            preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $name)
        );

        return $newName;
    }

    /**
     * Check if the filename already exists. If it does, append the filename with (number)
     */
    protected function checkForDuplicate($name)
    {

        if (
            Storage::disk($this->disk)
                ->exists($this->folder.DIRECTORY_SEPARATOR.$name)
        ) {
            $parts = pathinfo($name);
            $ext = isset($parts['extension']) ? ('.'.$parts['extension']) : '';
            $base = preg_replace('(\(\d\))', '', $parts['filename']);
            $number = 0;

            do {
                $name = $base.'('.++$number.')'.$ext;
            } while (
                Storage::disk($this->disk)
                    ->exists($this->folder.DIRECTORY_SEPARATOR.$name)
            );
        }

        return $name;
    }
}

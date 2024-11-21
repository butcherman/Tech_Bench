<?php

namespace App\Http\Controllers;

use App\Enums\DiskEnum;
use App\Exceptions\File\FileChunkMissingException;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

abstract class FileUploadController extends Controller
{
    /**
     * Storage Disk file will be saved to.
     *
     * @var string
     */
    protected $disk = 'local';

    /**
     * Folder file will be saved to
     *
     * @var string
     */
    protected $folder = 'tmp';

    /**
     * If the file is available for non-authenticated download
     *
     * @var bool
     */
    protected $isPublic = false;

    /*
    |---------------------------------------------------------------------------
    | Assign the disk, folder, and visibility for the file.
    |---------------------------------------------------------------------------
    */
    protected function setFileData(
        DiskEnum $disk,
        string $folder,
        ?bool $isPublic = false
    ): void {
        $this->disk = $disk->value;
        $this->folder = $folder;
        $this->isPublic = $isPublic;
    }

    /*
    |---------------------------------------------------------------------------
    | Capture a file chunk and store it.  If upload is completed, assemble
    | chunks and save the file.
    |---------------------------------------------------------------------------
    */
    protected function getChunk(UploadedFile $fileChunk, Request $request): FileUpload|false
    {
        $receiver = new FileReceiver(
            $fileChunk,
            $request,
            HandlerFactory::classFromRequest($request)
        );

        // Verify we are uploading a file
        if (! $receiver->isUploaded()) {
            throw new FileChunkMissingException;
        }

        $save = $receiver->receive();

        // File upload is completed
        if ($save->isFinished()) {
            $savedFile = $this->saveUploadedFile($save->getFile());

            return $savedFile;
        }

        // Chunking Mode, get file stats
        $handler = $save->handler();

        Log::debug('File Upload in progress', [
            'chunk-name' => $handler->getChunkFileName(),
            'percentage-done' => $handler->getPercentageDone(),
        ]);

        return false;
    }

    /**
     * Save the file and create database record
     */
    public function saveUploadedFile(UploadedFile $completedFile): FileUpload
    {
        $fileName = $this->saveFileToDisk($completedFile);

        return FileUpload::create([
            'disk' => $this->disk,
            'folder' => $this->folder,
            'file_name' => $fileName,
            'file_size' => $completedFile->getSize(),
            'public' => $this->isPublic,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | Process the file and save to proper folder.
    |---------------------------------------------------------------------------
    */
    public function saveFileToDisk(UploadedFile $file): string
    {
        $properName = $this->cleanFilename($file->getClientOriginalName());
        $fileName = $this->checkForDuplicate($properName);

        $file->storeAs($this->folder, $fileName, $this->disk);

        return $fileName;
    }

    /*
    |---------------------------------------------------------------------------
    | Sanitize the filename to remove any spaces or illegal characters.
    |---------------------------------------------------------------------------
    */
    public function cleanFilename(string $name): string
    {
        $newName = str_replace(
            ' ',
            '_',
            preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $name)
        );

        return $newName;
    }

    /*
    |---------------------------------------------------------------------------
    | Check if the filename already exists. If it does, append the filename
    | with an index number.
    |---------------------------------------------------------------------------
    */
    public function checkForDuplicate(string $name): string
    {
        if (
            Storage::disk($this->disk)
                ->exists($this->folder.DIRECTORY_SEPARATOR.$name)
        ) {
            // Index for appending filename
            $number = 0;

            $parts = pathinfo($name);

            // File Extension
            $ext = isset($parts['extension']) ? ('.'.$parts['extension']) : '';

            // Base filename without extension or folder
            $base = preg_replace('(\(\d\))', '', $parts['filename']);

            // Append filename until it is unique
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

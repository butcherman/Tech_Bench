<?php

namespace App\Http\Controllers;

use App\Enums\DiskEnum;
use App\Models\FileUpload;
use App\Traits\HandleFileTrait;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

abstract class FileUploadController extends Controller
{
    use HandleFileTrait;

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
        $fileName = $this->checkForDuplicate(
            $this->disk,
            $this->folder,
            $properName
        );

        $file->storeAs($this->folder, $fileName, $this->disk);

        return $fileName;
    }
}

<?php

namespace App\Services\File;

use App\Exceptions\File\FileMissingException;
use App\Models\FileUpload;
use App\Traits\HandleFileTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HandleFileService
{
    use HandleFileTrait;

    /**
     * Move a file to a new folder
     */
    public function moveFile(FileUpload $fileUpload, string $newFolder)
    {
        Log::debug('Preparing to move file to folder '.$newFolder, $fileUpload->toArray());

        // Do not move if the file is already in the move-to folder
        if ($fileUpload->folder === $newFolder) {
            return;
        }

        // Verify the file exists
        if (! $this->doesFileExist($fileUpload)) {
            throw new FileMissingException($fileUpload);
        }

        $fileName = $this->checkForDuplicate(
            $fileUpload->disk,
            $newFolder,
            $fileUpload->file_name
        );

        Storage::disk($fileUpload->disk)->move(
            $fileUpload->folder.DIRECTORY_SEPARATOR.$fileUpload->file_name,
            $newFolder.DIRECTORY_SEPARATOR.$fileName
        );

        Log::info('File Moved', [
            'file_disk' => $fileUpload->disk,
            'file_name' => $fileName,
            'from_folder' => $fileUpload->folder,
            'to_folder' => $newFolder,
        ]);

        $fileUpload->folder = $newFolder;
        $fileUpload->file_name = $fileName;
        $fileUpload()->save();

    }

    /**
     * Determine if the file exists in the file system.
     */
    public function doesFileExist(FileUpload $fileUpload): bool
    {
        return Storage::disk($fileUpload->disk)
            ->exists(
                $fileUpload->folder.DIRECTORY_SEPARATOR.$fileUpload->file_name
            );
    }

    /**
     * Set the public flag on an Uploaded File
     */
    public function setPublicFlag(FileUpload $fileUpload, bool $isPublic): void
    {
        $fileUpload->public = $isPublic;
        $fileUpload->save();
    }
}

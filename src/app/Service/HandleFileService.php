<?php

namespace App\Service;

use App\Exceptions\Filesystem\FileMissingException;
use App\Traits\FileTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HandleFileService
{
    use FileTrait;

    public function __construct()
    {
        //
    }

    /**
     * Move a file from one folder to another and update its location in DB
     */
    public function moveTmpFile(string $disk, string $newFolder, Collection $fileList)
    {
        Log::debug('Preparing to move temp files', $fileList->toArray());

        $this->setFileData($disk, $newFolder);

        foreach ($fileList as $uploadedFile) {
            // Verify that we are not moving the file to the folder it is already in
            if ($uploadedFile->folder === $newFolder) {
                return;
            }

            // Verify file does not already exist
            $fileName = $this->checkForDuplicate($uploadedFile->file_name);

            if (
                !Storage::disk($disk)
                    ->exists($uploadedFile->folder . DIRECTORY_SEPARATOR . $uploadedFile->file_name)
            ) {
                throw new FileMissingException($uploadedFile);
            }

            Storage::disk($disk)->move(
                $uploadedFile->folder . DIRECTORY_SEPARATOR . $uploadedFile->file_name,
                $newFolder . DIRECTORY_SEPARATOR . $fileName
            );

            Log::debug('Moved File ' . $uploadedFile->file_name, [
                'file_id' => $uploadedFile->file_id,
                'old_location' => $uploadedFile->folder,
                'new_location' => $newFolder,
            ]);

            $uploadedFile->folder = $newFolder;
            $uploadedFile->file_name = $fileName;
            $uploadedFile->save();
        }
    }

    public function setPublicFlag(Collection $fileList, $publicFlag)
    {
        foreach ($fileList as $file) {
            $file->public = $publicFlag;
            $file->save();
        }
    }
}

<?php

namespace App\Services\File;

use App\Facades\DbException;
use App\Models\FileUpload;
use Illuminate\Database\QueryException;

class FileUploadService extends FileStorageService
{
    /**
     * Move a file from one folder to another, and update the new folder in the
     * database.
     */
    public function moveUploadedFile(FileUpload $file, string $newFolder, ?string $newDisk = null): void
    {
        $currentPath = $file->folder.DIRECTORY_SEPARATOR.$file->file_name;
        $newPath = $newFolder.DIRECTORY_SEPARATOR.$file->file_name;

        $this->moveDiskFile($file->disk, $currentPath, $newPath, $newDisk);

        $file->folder = $newFolder;
        if ($newDisk) {
            $file->disk = $newDisk;
        }
        $file->save();
    }

    /**
     * Try to delete a file upload.  This process will fail if the upload is
     * currently a foreign key somewhere else in the database.
     */
    public function deleteFileUpload(FileUpload $file): bool
    {
        try {
            $file->delete();
        } catch (QueryException $e) {
            DbException::check($e);
        }

        $this->deleteDiskFile(
            $file->disk,
            $file->folder.DIRECTORY_SEPARATOR.$file->file_name
        );

        return true;
    }

    /**
     * Take an array of File ID's and delete the associated records.
     */
    public function deleteFileByID(array $idArray): void
    {
        $fileUploadList = FileUpload::find($idArray);

        foreach ($fileUploadList as $upload) {
            $this->deleteFileUpload($upload);
        }
    }
}

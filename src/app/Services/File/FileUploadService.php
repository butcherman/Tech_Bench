<?php

namespace App\Services\File;

use App\Facades\DbException;
use App\Models\FileUpload;
use Illuminate\Database\QueryException;

class FileUploadService extends FileStorageService
{
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
            $file->folder . DIRECTORY_SEPARATOR . $file->file_name
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

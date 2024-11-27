<?php

namespace App\Actions\File;

use App\Facades\DbException;
use App\Models\FileUpload;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteFileData
{
    /*
    |---------------------------------------------------------------------------
    | Attempt to delete a file from the file_uploads table.  If the deletion is
    | successful, delete the file from the storage system.  A deletion will
    | fail if the file is still attached as a foreign key to another DB
    | entry.
    |---------------------------------------------------------------------------
    */
    public function __invoke(FileUpload $fileUpload)
    {
        try {
            $path = $fileUpload
                ->folder.DIRECTORY_SEPARATOR.$fileUpload
                ->file_name;

            $fileUpload->delete();
            Storage::disk($fileUpload->disk)->delete($path);

            Log::notice(
                'File '.$path.' on disk '.$fileUpload->disk.' has been deleted'
            );
        } catch (QueryException $e) {
            Log::error('Delete File Failed - '.$e->getMessage());

            DbException::check($e);
        }
    }
}

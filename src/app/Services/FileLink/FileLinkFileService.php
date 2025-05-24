<?php

namespace App\Services\FileLink;

use App\Models\FileLink;
use App\Services\File\FileUploadService;

class FileLinkFileService extends FileUploadService
{
    /**
     * Move any files that are in the tmp folder to the proper folder for link.
     */
    public function checkLinkFileFolder(FileLink $link): void
    {
        $fileList = $link->Files;

        foreach ($fileList as $file) {
            if ($file->disk === 'fileLinks' && $file->folder === 'tmp') {
                $this->moveUploadedFile($file, $link->link_id);
            }
        }
    }

    /**
     * Determine if the files should be listed as public or private
     */
    public function checkLinkFilePermission(FileLink $link): void
    {
        $fileList = $link->Files;

        foreach ($fileList as $file) {
            if (!$file->pivot->upload) {
                $file->public = true;
                $file->save();
            }
        }
    }
}

<?php

namespace App\Services\TechTip;

use App\Models\TechTip;
use App\Services\File\FileUploadService;

class TechTipFileService extends FileUploadService
{
    /**
     * Move any files that are not in the proper folder for the Tech Tip.
     */
    public function checkTipFileFolder(TechTip $techTip): void
    {
        $fileList = $techTip->Files;

        foreach ($fileList as $file) {
            if ($file->folder != $techTip->tip_id) {
                $this->moveUploadedFile($file, $techTip->tip_id);
            }
        }
    }
}

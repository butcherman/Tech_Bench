<?php

namespace App\Services\File;

use App\Traits\HandleFileTrait;
use Illuminate\Support\Facades\Storage;

class FileMaintenanceService
{
    use HandleFileTrait;

    /**
     * Get the size of a storage disk.
     */
    public function getStorageDiskSize(string $disk)
    {
        $size = 0;

        $fileList = Storage::disk($disk)->allFiles();
        foreach ($fileList as $file) {
            $size += Storage::disk($disk)->size($file);
        }

        return $size;
    }
}

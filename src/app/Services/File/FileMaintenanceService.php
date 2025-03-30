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
    public function getStorageDiskSize(string $disk): int
    {
        $size = 0;

        $fileList = Storage::disk($disk)->allFiles();
        foreach ($fileList as $file) {
            $size += Storage::disk($disk)->size($file);
        }

        return $size;
    }

    /**
     * Get the Free Space available for a storage disk.
     */
    public function getDiskFreeSpace(string $disk): int
    {
        $path = Storage::disk($disk)->path('');

        return disk_free_space($path);
    }
}

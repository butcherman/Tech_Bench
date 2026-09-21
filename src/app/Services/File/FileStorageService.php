<?php

namespace App\Services\File;

use App\Enums\DiskEnum;
use App\Exceptions\File\FileMissingException;
use App\Traits\HandleFileTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileStorageService
{
    use HandleFileTrait;

    /**
     * Move a file from one folder to another.
     */
    public function moveDiskFile(
        DiskEnum $disk,
        string $currentPath,
        string $newPath,
        ?DiskEnum $newDisk = null
    ): void {
        $this->checkForDiskFile($disk, $currentPath);

        $newInfo = pathinfo($newPath);

        $fileName = $this->checkForDuplicate(
            $newDisk ?? $disk,
            $newInfo['dirname'],
            $newInfo['basename']
        );

        $properPath = $newInfo['dirname'].DIRECTORY_SEPARATOR.$fileName;

        if ($newDisk) {
            $currentFullPath = Storage::disk($disk->value)->path($currentPath);
            $newFullPath = Storage::disk($newDisk->value)->path($properPath);
            $newDirPath = pathinfo($newFullPath)['dirname'];

            File::ensureDirectoryExists($newDirPath);
            File::move($currentFullPath, $newFullPath);

            return;
        }

        Storage::disk($disk->value)->move($currentPath, $properPath);
    }

    /**
     * Delete a file from a storage disk.
     */
    public function deleteDiskFile(DiskEnum $disk, string $path): void
    {
        $this->checkForDiskFile($disk, $path);

        Storage::disk($disk->value)->delete($path);
    }

    /**
     * Verify if a file exists or not.  Throw exception if it is missing.
     */
    protected function checkForDiskFile(DiskEnum $disk, string $path): void
    {
        if (! Storage::disk($disk->value)->exists($path)) {
            throw new FileMissingException($disk->value.DIRECTORY_SEPARATOR.$path);
        }
    }
}

<?php

namespace App\Services\_Base;

use App\Traits\HandleFileTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

abstract class FileMaintenanceService
{
    use HandleFileTrait;

    /**
     * Files that are part of the application scaffolding.
     *
     * @var array<int, string>
     */
    protected $scaffoldFiles = ['.gitignore'];

    /**
     * Get the Free Space available for a storage disk.
     */
    public function getDiskFreeSpace(string $disk): int
    {
        $path = Storage::disk($disk)->path('');

        return disk_free_space($path);
    }

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
     * Get a list of files within a directory.  Skip any files that are part of
     * the application scaffolding.
     */
    public function getFileList($basePath): array
    {
        $allFiles = File::allFiles($basePath, true);

        // Find and remove scaffolding files.
        foreach ($this->scaffoldFiles as $scFile) {
            $scaffoldList = preg_grep('/'.$scFile.'/i', $allFiles);

            foreach ($scaffoldList as $key => $file) {
                unset($allFiles[$key]);
            }
        }

        return array_values($allFiles);
    }

    /**
     * Return a list of empty directories within a base directory.
     */
    public function getEmptyDirectories($basePath): array
    {
        $directoryList = File::directories($basePath);
        $emptyList = [];

        foreach ($directoryList as $directory) {
            $fileList = File::allFiles($directory, true);
            if (empty($fileList)) {
                $emptyList[] = $directory;
            }
        }

        return $emptyList;
    }

    /**
     * Recursively delete a directory and all files inside.  During wipe
     * process, we will check to see if a .gitignore file is included
     * in the directory.  If it is, then the folder is part of the
     * application scaffolding and the folder is not deleted.
     */
    protected function wipeDirectory(string $dirPath): void
    {
        $fileList = $this->getFileLIst($dirPath);

        // Delete all the files from the disk
        foreach ($fileList as $file) {
            File::delete($file);
            Log::notice('File Deleted - '.$file);
        }

        // Delete all the empty directories from the disk
        $dirList = $this->getEmptyDirectories($dirPath);
        foreach ($dirList as $dir) {
            File::deleteDirectory($dir);
            Log::notice('Directory Deleted - '.$dir);
        }
    }
}

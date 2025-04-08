<?php

namespace App\Services\Maintenance;

use App\Facades\DbException;
use App\Models\CustomerFile;
use App\Models\FileLinkFile;
use App\Models\FileUpload;
use App\Models\TechTipFile;
use App\Traits\HandleFileTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileMaintenanceService
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
    public function getDiskFreeSpace(string $disk, bool $humanReadable = false): int|string
    {
        $path = Storage::disk($disk)->path('');

        $size = disk_free_space($path);

        if ($humanReadable) {
            return $this->readableFileSize($size);
        }

        return $size;
    }

    /**
     * Get the used size of a specific storage disk.
     */
    public function getStorageDiskSize(string $disk, bool $humanReadable = false): int|string
    {
        $size = 0;

        $fileList = Storage::disk($disk)->allFiles();
        foreach ($fileList as $file) {
            $size += Storage::disk($disk)->size($file);
        }

        if ($humanReadable) {
            return $this->readableFileSize($size);
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
            $scaffoldList = preg_grep('/' . $scFile . '/i', $allFiles);

            foreach ($scaffoldList as $key => $file) {
                unset($allFiles[$key]);
            }
        }

        return array_values($allFiles);
    }

    /**
     * Return a list of empty directories within a base directory.
     */
    public function getEmptyDirectories(string $basePath): array
    {
        $directoryList = File::directories($basePath);
        $emptyList = [];

        foreach ($directoryList as $directory) {
            $fileList = File::allFiles($directory, true);
            if (empty($fileList)) {
                $emptyList[] = $directory;
            } else {
                $subList = $this->getEmptyDirectories($directory);
                $emptyList = array_merge($emptyList, $subList);
            }
        }

        return $emptyList;
    }

    /**
     * Go through all files in the file_uploads table to make sure that the
     * file exists.
     */
    public function getMissingFiles(): Collection
    {
        $fileList = FileUpload::all();

        foreach ($fileList as $key => $file) {
            $filePath = trim(rtrim($file->folder, '/'), '/') . DIRECTORY_SEPARATOR . $file->file_name;
            if (Storage::disk($file->disk)->exists($filePath)) {
                unset($fileList[$key]);
            }
        }

        return $fileList;
    }

    /**
     * Go through all files in the filesystem to make sure that each one has
     * a database entry.  Note: scaffold files are not included.
     */
    public function getOrphanedFiles(): array
    {
        $fileList = $this->getFileList(Storage::path(''));

        foreach ($fileList as $key => $file) {
            $fileInfo = pathinfo($file);

            if (Str::contains($fileInfo['dirname'], ['public', 'private'])) {
                unset($fileList[$key]);
                continue;
            }

            $dbFile = FileUpload::whereFileName($fileInfo['basename'])->first();

            if ($dbFile) {
                $databasePath = Storage::disk($dbFile->disk)
                    ->path($dbFile->folder . DIRECTORY_SEPARATOR . $dbFile->file_name);
                $storagePath = Storage::disk('local')->path($file);

                if ($databasePath === $storagePath) {
                    unset($fileList[$key]);
                }
            }
        }

        return $fileList;
    }

    /**
     * Recursively delete a directory and all files inside.  During wipe
     * process, we will check to see if a .gitignore file is included
     * in the directory.  If it is, then the folder is part of the
     * application scaffolding and the folder is not deleted.
     */
    public function wipeDirectory(string $dirPath): void
    {
        $fileList = $this->getFileList($dirPath);

        // Delete all the files from the disk
        foreach ($fileList as $file) {
            File::delete($file);
            Log::notice('File Deleted - ' . $file);
        }

        // Delete all the empty directories from the disk
        $dirList = $this->getEmptyDirectories($dirPath);
        foreach ($dirList as $dir) {
            File::deleteDirectory($dir);
            Log::notice('Directory Deleted - ' . $dir);
        }
    }

    /**
     * Force delete a file and any referenced foreign key that may be
     * attached to the file.
     */
    public function forceDeleteFileUpload(FileUpload $fileUpload): void
    {
        TechTipFile::whereFileId($fileUpload->file_id)->delete();
        FileLinkFile::whereFileId($fileUpload->file_id)->delete();
        CustomerFile::withTrashed()
            ->whereFileId($fileUpload->file_id)
            ->forceDelete();

        try {
            $fileUpload->delete();
        } catch (QueryException $e) {
            DbException::check($e);
        }
    }
}

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

        Log::debug('Get Disk Free Space - ' . $size);

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
            $fileSize = Storage::disk($disk)->size($file);

            Log::debug('Checking File Size for ' . $file, [
                'size' => $fileSize,
            ]);

            $size += $fileSize;
        }

        Log::debug('Get Storage Disk Size - ' . $size);

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

        Log::debug('Getting file list for path - ' . $basePath, [
            'file_list' => $allFiles,
        ]);

        // Find and remove scaffolding files.
        foreach ($this->scaffoldFiles as $scFile) {
            $scaffoldList = preg_grep('/' . $scFile . '/i', $allFiles);

            foreach ($scaffoldList as $key => $file) {
                Log::debug('Removing Scaffolding File from file list - ' . $file);

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
                Log::debug('Found empty directory at ' . $directory);
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

        Log::debug('Checking files missing from the file_uploads table', [
            'file_list' => $fileList,
        ]);

        foreach ($fileList as $key => $file) {
            $filePath = trim(
                rtrim(
                    $file->folder,
                    '/'
                ),
                '/'
            ) . DIRECTORY_SEPARATOR . $file->file_name;

            Log::debug('Checking for file at ' . Storage::disk($file->disk)->path($filePath));

            if (Storage::disk($file->disk)->exists($filePath)) {
                Log::debug('File ' . $filePath . ' exists.  Removing from list');

                unset($fileList[$key]);
            }
        }

        Log::debug('Files missing from filesystem with database pointers', [
            'count' => count($fileList),
            'file_list' => $fileList,
        ]);

        return $fileList;
    }

    /**
     * Go through all files in the filesystem to make sure that each one has
     * a database entry.  Note: scaffold files are not included.
     */
    public function getOrphanedFiles(): array
    {
        $fileList = $this->getFileList(Storage::path(''));

        Log::debug('Checking for files without a database pointer', [
            'file_list' => $fileList,
        ]);

        foreach ($fileList as $key => $file) {
            $fileInfo = pathinfo($file);

            Log::debug(
                'Checking file ' . $file,
                $fileInfo
            );

            if (Str::contains($fileInfo['dirname'], ['public', 'private'])) {
                unset($fileList[$key]);

                Log::debug('Public or Private file found.  Skipping', [
                    'file_name' => $file,
                ]);

                continue;
            }

            $dbFiles = FileUpload::whereFileName($fileInfo['basename'])->get();

            if ($dbFiles->isNotEmpty()) {
                foreach ($dbFiles as $dbFile) {

                    $databasePath = Storage::disk($dbFile->disk)
                        ->path($dbFile->folder . DIRECTORY_SEPARATOR . $dbFile->file_name);
                    $storagePath = $file->getRealPath();

                    Log::debug(
                        'Database Entry found for ' . $file . '. Validating proper location',
                        [
                            'database_path' => $databasePath,
                            'storage_path' => $storagePath,
                        ]
                    );

                    if ($databasePath === $storagePath) {
                        Log::debug('File location validated.  Removing from list');

                        unset($fileList[$key]);

                        break;
                    }
                }
            } else {
                Log::debug('No database entry found for ' . $file);
            }
        }

        Log::debug('Orphaned file list', [
            'file_list' => $fileList,
        ]);

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
        Log::debug('Force deleting File Upload entry', $fileUpload->toArray());

        TechTipFile::whereFileId($fileUpload->file_id)->delete();
        FileLinkFile::whereFileId($fileUpload->file_id)->delete();
        CustomerFile::withTrashed()
            ->whereFileId($fileUpload->file_id)
            ->forceDelete();

        try {
            $fileUpload->delete();
            // @codeCoverageIgnoreStart
        } catch (QueryException $e) {
            DbException::check($e);
            // @codeCoverageIgnoreEnd
        }
    }
}

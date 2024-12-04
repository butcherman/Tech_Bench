<?php

namespace App\Services\File;

use App\Models\CustomerFile;
use App\Models\FileUpload;
use App\Models\TechTipFile;
use App\Traits\HandleFileTrait;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Helper\ProgressBar;

class FileMaintenanceService
{
    use HandleFileTrait;

    /**
     * Files that should be ignored by this class.
     *
     * @var array
     */
    protected $specialFiles = ['.gitignore'];

    /**
     * Folders that should be ignored by this class.
     *
     * @var array
     */
    protected $specialFolder = ['public', 'chunks'];

    /**
     * Get Free and Used Disk Space
     */
    public function getDiskSpace(): array
    {
        $freeSpace = disk_free_space('/app');
        $totalSpace = disk_total_space('/app');
        $usedSpace = $totalSpace - $freeSpace;

        return [
            'free_space' => $this->readableFileSize($freeSpace),
            'used_space' => $this->readableFileSize($usedSpace),
            'total_space' => $this->readableFileSize($totalSpace),
            'percentage' => round(($usedSpace / $totalSpace * 100), 2).'%',
        ];
    }

    /**
     * Get a list of all Storage Directories
     */
    public function getDirectoryList(): array
    {
        return Storage::allDirectories();
    }

    /**
     * Get a list of empty directories from the given array of directories
     */
    public function getEmptyDirectories(
        array $directoryList,
        bool $fix,
        ?ProgressBar $progressBar
    ): array {
        $emptyList = [];

        foreach ($directoryList as $directory) {
            if ($directory === 'chunks' || $directory === 'public') {
                continue;
            }

            $files = count(Storage::files($directory));
            $dirList = count(Storage::directories($directory));

            if (! $files && ! $dirList) {
                $emptyList[] = $directory;

                if ($fix) {
                    Storage::deleteDirectory($directory);
                }
            }

            if ($progressBar) {
                $progressBar->advance();
            }
        }

        return $emptyList;
    }

    /**
     * Return a list of all files in the filesystem
     * Remove any special use folders and files
     */
    public function getFileList(): array
    {
        $allFiles = Storage::disk('local')->allFiles();

        // Remove any special files
        foreach ($this->specialFiles as $spFile) {
            $specialList = preg_grep('/'.$spFile.'/i', $allFiles);
            foreach ($specialList as $key => $special) {
                unset($allFiles[$key]);
            }
        }

        // Remove any special folders
        foreach ($this->specialFolder as $spFolder) {
            $specialList = preg_grep('/^'.$spFolder.'\//i', $allFiles);
            foreach ($specialList as $key => $special) {
                unset($allFiles[$key]);
            }
        }

        return $allFiles;
    }

    /**
     * Put together a list of missing files and files without database pointers
     */
    public function findMissingFiles(bool $fix, ?ProgressBar $progressBar): array
    {
        $missingList = [];
        $dbList = FileUpload::all();

        foreach ($dbList as $dbFile) {
            // Trim any extra slashes from folder
            if ($fix) {
                $dbFile->folder = trim(rtrim($dbFile->folder, '/'), '/');
            }

            // Verify that the file exists
            if (
                Storage::disk($dbFile->disk)
                    ->missing(
                        $dbFile->folder.DIRECTORY_SEPARATOR.$dbFile->file_name
                    )
            ) {
                $missingList[] = [
                    'file_id' => $dbFile->file_id,
                    'disk' => $dbFile->disk,
                    'file_name' => $dbFile->file_name,
                ];

                if ($fix) {
                    $this->clearFileUploadEntry($dbFile);
                }
            }

            if ($progressBar) {
                $progressBar->advance();
            }
        }

        return $missingList;
    }

    /**
     * Get a list of all files that do not have database pointers
     */
    public function findOrphanedFiles(
        array $fileList,
        bool $fix,
        ProgressBar $progressBar
    ): array {
        $orphaned = [];
        foreach ($fileList as $fileUpload) {
            $parts = pathinfo($fileUpload);
            $dbFile = FileUpload::where('file_name', $parts['basename'])->get();

            // If file was not found, move on to the next
            if (! $dbFile) {
                $orphaned[] = $fileUpload;

                if ($fix) {
                    Storage::disk('local')->delete($fileUpload);
                }
            } else {
                // Determine that the file is in the proper folder
                $valid = false;
                foreach ($dbFile as $db) {
                    $dbPath = Storage::disk($db->disk)
                        ->path($db->folder.DIRECTORY_SEPARATOR.$db->file_name);
                    $stPath = Storage::disk('local')->path($fileUpload);

                    if ($dbPath === $stPath) {
                        $valid = true;
                    }
                }

                if (! $valid) {
                    $orphaned[] = $fileUpload;

                    if ($fix) {
                        Storage::disk('local')->delete($fileUpload);
                    }
                }
            }

            if ($progressBar) {
                $progressBar->advance();
            }

        }

        return $orphaned;
    }

    /**
     * Remove a file from the File Uploads - remove any foreign key pointers first
     */
    protected function clearFileUploadEntry(FileUpload $fileUpload): void
    {
        Log::notice('Deleted Missing File pointer', $fileUpload->toArray());

        CustomerFile::withTrashed()
            ->whereFileId($fileUpload->file_id)
            ->forceDelete();
        TechTipFile::whereFileId($fileUpload->file_id)->delete();
        // FileLinkFile::whereFileId($fileUpload->file_id)->delete();
        // TODO - ADD BACK

        try {
            $fileUpload->delete();
        } catch (Exception $e) {
            Log::error('Problem while trying to delete file - '.$e->getMessage());
        }
    }
}

<?php

namespace App\Service\Maint;

use App\Models\CustomerFile;
use App\Models\FileLinkFile;
use App\Models\FileUpload;
use App\Models\TechTipFile;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Helper\ProgressBar;

class FileMaintenanceService
{
    public function __construct(protected bool $fix = false)
    {
        //
    }

    /**
     * Get a list of all Storage Directories
     */
    public function getDirectoryList()
    {
        return Storage::allDirectories();
    }

    /**
     * Return a list of all files in the filesystem
     * Remove any special use folders and files
     */
    public function getFileList()
    {
        $specialFiles = ['.gitignore'];
        $specialFolder = ['public', 'chunks'];

        $allFiles = Storage::disk('local')->allFiles();

        // Remove any special files
        foreach ($specialFiles as $spFile) {
            $specialList = preg_grep('/'.$spFile.'/i', $allFiles);
            foreach ($specialList as $key => $special) {
                unset($allFiles[$key]);
            }
        }

        // Remove any special folders
        foreach ($specialFolder as $spFolder) {
            $specialList = preg_grep('/^'.$spFolder.'\//i', $allFiles);
            foreach ($specialList as $key => $special) {
                unset($allFiles[$key]);
            }
        }

        return $allFiles;
    }

    /**
     * Get a list of all files that do not have database pointers
     */
    public function getOrphanedFiles(array $fileList, ProgressBar $progressBar)
    {
        Log::debug('Checking for Orphaned Files');

        $orphaned = [];
        foreach ($fileList as $fileUpload) {
            $parts = pathinfo($fileUpload);
            $dbFile = FileUpload::where('file_name', $parts['basename'])->get();

            // If file was not found, move on to the next
            if (! $dbFile) {
                $orphaned[] = $fileUpload;

                if ($this->fix) {
                    Log::notice('Deleting Orphaned File - '.$fileUpload);
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

                    if ($this->fix) {
                        Log::notice('Deleting Orphaned File - '.$fileUpload);
                        Storage::disk('local')->delete($fileUpload);
                    }
                }
            }

            $progressBar->advance();

        }

        return $orphaned;
    }

    /**
     * Get a list of empty directories
     */
    public function getEmptyDirectories(array $directoryList, ProgressBar $progressBar)
    {
        Log::debug('Checking for empty directories');

        $emptyList = [];

        foreach ($directoryList as $directory) {
            if ($directory === 'chunks' || $directory === 'public') {
                continue;
            }

            $files = count(Storage::files($directory));
            $dirList = count(Storage::directories($directory));
            Log::debug('Directory '.$directory.' files:', [
                'files' => $files,
                'sub-directories' => $dirList,
            ]);

            if (! $files && ! $dirList) {
                $emptyList[] = $directory;
                Log::debug('Empty Directory Found '.$directory);

                if ($this->fix) {
                    Log::notice('Deleting empty Directory - '.$directory);
                    Storage::deleteDirectory($directory);
                }
            }

            $progressBar->advance();
        }

        return $emptyList;
    }

    /**
     * Put together a list of missing files and files without database pointers
     */
    public function findMissingFiles(ProgressBar $progressBar)
    {
        Log::debug('Checking for missing files');

        $missingList = [];
        $dbList = FileUpload::all();

        foreach ($dbList as $dbFile) {
            // Trim any extra slashes from folder
            if ($this->fix) {
                $dbFile->folder = trim(rtrim($dbFile->folder, '/'), '/');
            }

            // Verify that the file exists
            if (Storage::disk($dbFile->disk)->missing($dbFile->folder.DIRECTORY_SEPARATOR.$dbFile->file_name)) {
                Log::notice('Found Missing File', $dbFile->toArray());
                $missingList[] = [
                    'file_id' => $dbFile->file_id,
                    'disk' => $dbFile->disk,
                    'file_name' => $dbFile->file_name,
                ];

                if ($this->fix) {
                    $this->clearFileUploadEntry($dbFile);
                }
            }

            $progressBar->advance();
        }

        return $missingList;
    }

    /**
     * Remove a file from the File Uploads - remove any foreign key pointers first
     */
    protected function clearFileUploadEntry(FileUpload $fileUpload)
    {
        Log::notice('Deleted Missing File pointer', $fileUpload->toArray());

        CustomerFile::withTrashed()->whereFileId($fileUpload->file_id)->forceDelete();
        TechTipFile::whereFileId($fileUpload->file_id)->delete();
        FileLinkFile::whereFileId($fileUpload->file_id)->delete();

        try {
            $fileUpload->delete();
        } catch (Exception $e) {
            Log::error('Problem while trying to delete file - '.$e->getMessage());
        }
    }

    /**
     * Get Free and Used Disk Space
     */
    public function getDiskSpace()
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
     * Simple function to convert size in bytes to readable file size
     */
    protected function readableFileSize(int $bytes, int $decimals = 2)
    {
        $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)).@$size[$factor];
    }
}

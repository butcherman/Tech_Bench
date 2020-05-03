<?php

namespace App\Domains;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Zip;
use Carbon\Carbon;

use App\Files;

class DownloadDomain
{
    protected $file, $user, $file_id, $file_name;

    public function __construct()
    {
        if(Auth::check())
        {
            $this->user = Auth::user()->full_name;
        }
        else
        {
            $this->user = \Request::ip();
        }
    }

    public function setFileDetails($fileID, $fileName)
    {
        $this->file_id   = $fileID;
        $this->file_name = $fileName;
        $this->file = Files::where('file_id', $fileID)->where('file_name', $fileName)->first();
        Log::debug('File Details set for new file download by '.$this->user.'.  Details - ', array($this->file));
    }

    public function getFileLinkForDownload()
    {
        Log::info('User '.$this->user.' is downloading a file.  Details - ', array($this->file));
        return $this->file->file_link.$this->file->file_name;
    }

    //  Determine if the file is actually a valid file - name and ID must match
    public function isFileValid()
    {
        $valid = $this->file ? true : false;
        if(!$valid)
        {
            Log::error('User '.$this->user.' is attempting to download an invalid file.  Details - ', ['File ID' => $this->file_id, 'File Name' => $this->file_name]);
        }
        else if(!Storage::exists($this->file->file_link.$this->file->file_name))
        {
            $valid = false;
            Log::error('User '.$this->user.' is attempting to download a missing file.  Details - ', ['File ID' => $this->file_id, 'File Name' => $this->file_name, 'File Path' => $this->file->file_link]);
        }

        return $valid;
    }

    //  Determine if the file is public or if the user is logged in
    public function canUserDownload()
    {
        if($this->file->public || Auth::check())
        {
            return true;
        }

        Log::error('User '.$this->user.' is attempting to download a file they do not have permission to download.  Details - ', ['File ID' => $this->file_id, 'File Name' => $this->file_name]);
        return false;
    }

    //  Create an archive of files that the user can download in a single zip
    public function createArchive($fileArr)
    {
        //  Create the zip archive structure
        $rootPath    = config('filesystems.disks.local.root').DIRECTORY_SEPARATOR;
        $archivePath = 'archive_downloads'.DIRECTORY_SEPARATOR;
        $archiveName = 'zip_archive_'.Carbon::now()->timestamp.'.zip';
        Storage::put($archivePath.'.ignore', '');
        $absolutePath = $rootPath.$archivePath.$archiveName;

        //  Create the zip archive
        $zip = Zip::create($absolutePath);
        Log::info('Zip archive '.$absolutePath.' opened for user '.$this->user);

        //  Add files to the archive
        foreach($fileArr as $file)
        {
            $this->setFileDetails($file['file_id'], $file['file_name']);
            if($this->isFileValid() && $this->canUserDownload())
            {
                $filePath = $this->file->file_link.$this->file->file_name;
                $zip->add($rootPath.$filePath);
                Log::info('File '.$filePath.' added to File Archive '.$archiveName.' by '.$this->user);
            }
        }

        //  Close the zip archive
        $zip->close();

        Log::info('Zip archive '.$archiveName.' creation completed');
        return $archiveName;
    }

    //  Validate that a given archive name exists - returns the full path of the archive
    public function validateArchive($archiveName)
    {
        $rootPath    = config('filesystems.disks.local.root').DIRECTORY_SEPARATOR;
        $archivePath = 'archive_downloads'.DIRECTORY_SEPARATOR;

        if(Storage::exists($archivePath.$archiveName))
        {
            return $rootPath.$archivePath.$archiveName;
        }

        return false;
    }

    //  Delete the archive after it has been downloaded
    public function deleteArchive($archiveName)
    {
        Storage::delete('archive_downloads'.DIRECTORY_SEPARATOR.$archiveName);
        Log::debug('Archive '.$archiveName.' deleted after download');
    }
}

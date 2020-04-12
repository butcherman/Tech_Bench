<?php

namespace App\Domains;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Files;

class FilesDomain
{
    protected $receiver, $path, $fileID;

    //  Constructor will set a default path location in the event one is not set through child class
    public function __construct()
    {
        $this->path = config('filesystems.paths.default');
    }

    //  Save a file after it has been uploaded
    protected function saveFile($file)
    {
        $fileName = $this->cleanFilename($file->getClientOriginalName());
        $fileName = $this->isFileDup($fileName);
        $file->storeAs($this->path, $fileName);

        //  Place file in Files table of DB
        $newFile = Files::create([
            'file_name' => $fileName,
            'file_link' => $this->path.DIRECTORY_SEPARATOR
        ]);

        return  $newFile->file_id;
    }

    //  Try to delete a file - note this will fail if it is still in use
    protected function deleteFile($fileID)
    {
        try
        {
            //  Try to delete file from database - will throw error if foreign key is in use
            $fileData = Files::find($fileID);
            $fileLink = $fileData->file_link.$fileData->file_name;
            $fileData->delete();
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            //  Unable to remove file from the database
            Log::warning('Attempt to delete file failed.  Reason - '.$e.'. Additional Data - ', ['file_id' => $fileID, 'file_name' => $fileLink, 'user_id' => Auth::user()->user_id]);
            return false;
        }

        //  Delete the file from the storage system
        Storage::delete($fileLink);

        Log::notice('File deleted by '.Auth::user()->full_name.'. Additional Information - ', ['file_id' => $fileID, 'file_name' => $fileLink, 'user_id' => Auth::user()->user_id]);
        return true;
    }

    //  Clean a file name to remove any spaces
    protected function cleanFilename($name)
    {
        //  Remove all spaces
        $fileName = str_replace(' ', '_', $name);

        return $fileName;
    }

    //  Determine if the already exists and should be appended
    protected function isFileDup($fileName)
    {
        //  Determine if the filename already exists
        if (Storage::exists($this->path . DIRECTORY_SEPARATOR . $fileName))
        {
            $fileParts = pathinfo($fileName);
            $extension = isset($fileParts['extension']) ? ('.' . $fileParts['extension']) : '';

            //  Look to see if a number is already appended to a file.  (example - file(1).pdf)
            if (preg_match('/(.*?)(\d+)$/', $fileParts['filename'], $match))
            {
                // Has a number, increment it
                $base = $match[1];
                $number = intVal($match[2]);
            }
            else
            {
                // No number, add one
                $base = $fileParts['filename'];
                $number = 0;
            }

            //  Increase the number until one that is not in use is found
            do
            {
                $fileName = $base . '(' . ++$number . ')' . $extension;
            } while (Storage::exists($this->path.DIRECTORY_SEPARATOR.$fileName));
        }

        return $fileName;
    }

    //  When multiple files are uploaded at once, they are placed in a temporary location.  This moves them to the final folder
    protected function moveFile($newPath, $fileID)
    {
        $data = Files::find($fileID);
        //  Move the file to the proper folder
        try{

            Storage::move($data->file_link.$data->file_name, $newPath.DIRECTORY_SEPARATOR.$data->file_name);
        }
        catch(\Exception $e)
        {
            report($e);
            return false;
        }

        //  Update file link in DB
        $data->update([
            'file_link' => $newPath.DIRECTORY_SEPARATOR
        ]);

        return true;
    }
}

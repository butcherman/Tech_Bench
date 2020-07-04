<?php

namespace App\Domains\Files;

use App\Files;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GetFiles
{
    //  Determine if a file exists in the database, and in the storage system.  The file name and file id must match
    public function validateFile($fileID, $fileName)
    {
        $fileData = Files::where('file_id', $fileID)->where('file_name', $fileName)->first();

        if(!$fileData || !Storage::exists($fileData->file_link.$fileData->file_name))
        {
            Log::error('Unable to locate filename '.$fileName.' at path '.$fileData->file_link);
            return false;
        }

        return $fileData->file_link.$fileData->file_name;
    }

    //  Only logged in users can download files not marked as public
    public function canUserDownload($fileID, $authorized)
    {
        $fileData = Files::find($fileID);

        if(!$authorized && !$fileData->public)
        {
            Log::error('Public user is trying to download a private file ID '.$fileID);
            return false;
        }

        return true;
    }
}

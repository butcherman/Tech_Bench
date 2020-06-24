<?php

namespace App\Domains\Files;

use App\Files;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GetFiles
{
    public function validateFile($fileID, $fileName)
    {
        $fileData = Files::where('file_id', $fileID)->where('file_name', $fileName)->first();

        if(!$fileData || !Storage::exists($fileData->file_link.$fileData->file_name))
        {
            return false;
        }

        return $fileData->file_link.$fileData->file_name;
    }

    public function canUserDownload($fileID, $authorized)
    {
        $fileData = Files::find($fileID);

        if(!$authorized && !$fileData->public)
        {
            return false;
        }

        return true;
    }
}
